<?php

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\PaymentMethod;
use Uocnv\BaokimPayment\Exceptions\InvalidMrcOrderIdException;
use Uocnv\BaokimPayment\Exceptions\InvalidSignatureException;
use Uocnv\BaokimPayment\Exceptions\UnknownPaymentMethodException;
use Uocnv\BaokimPayment\Lib\BaoKimJWT;
use Uocnv\BaokimPayment\Lib\Helper;

class JWTClient
{
    public static string $paymentMethod = '';

    public static function makeClient(): Client
    {
        $host = config('baokim-payment.jwt.host');
        return new Client([
            'headers'  => [
                'Content-Type' => "application/json",
            ],
            'base_uri' => $host
        ]);
    }

    /**
     * Request to BaoKim
     *
     * @param int $transactionId
     * @param int $amount
     * @param int $bankId
     * @param string $referer
     * @param string $userEmail
     * @param string $userPhone
     * @return array|null
     */
    public static function request(
        int $transactionId,
        int $amount,
        string $referer,
        int $bankId = 0,
        string $userEmail = '',
        string $userPhone = ''
    ): ?array {
        $paymentMethod = self::$paymentMethod;

        $config   = config("baokim-payment.jwt.{$paymentMethod}");
        $uri      = Arr::get($config, 'uri_api');
        $arrayKey = Arr::get($config, 'secret_key');
        $key      = Helper::getRandomWeight($arrayKey) ?: Arr::first(array_keys($arrayKey));

        if (!PaymentMethod::hasValue($paymentMethod)) {
            return null;
        }

        try {
            DB::table('transactions_keys')->insert([
                'key_used'         => $key,
                'transaction_id'   => $transactionId,
                'transaction_type' => config("baokim-payment.jwt.{$paymentMethod}.transaction_type")
            ]);

            $bpmId   = $bankId ?: config("baokim-payment.jwt.{$paymentMethod}.bpm_id");
            $orderId = config('baokim-payment.jwt.key', '123doc') . '.' . $paymentMethod . '_' . $transactionId;

            $postArgs = [
                'bpm_id'         => $bpmId,
                'merchant_id'    => config("baokim-payment.jwt.{$paymentMethod}.secret_key.{$key}.merchant_id"),
                'mrc_order_id'   => $orderId,
                'total_amount'   => $amount,
                'description'    => "Thanh toán Mã: {$orderId}",
                'url_success'    => $referer,
                'accept_qrpay'   => 1,
                'accept_cc'      => 1,
                'customer_email' => $userEmail ?: 'info@123doc.org',
                'customer_phone' => $userPhone ?: '0123456789',
                'current_id'     => $transactionId,
                'webhooks'       => config("baokim-payment.jwt.{$paymentMethod}.webhook")
            ];

            if (config('baokim-payment.log.request')) {
                Log::channel(config('baokim-payment.log.chanel'))->info("Request for orderId: #{$orderId}", $postArgs);
            }

            BaoKimJWT::$dataPost = $postArgs;
            $jwt                 = BaoKimJWT::refreshToken($paymentMethod, $key);

            $client   = self::makeClient();
            $response = $client->post("{$uri}?jwt={$jwt}", [
                'form_params' => $postArgs,
            ]);
            unset($client);
            $responseTxt   = $response->getBody()->getContents();
            $responseArray = json_decode($responseTxt, true);
            if ($response->getStatusCode() == 200 && Arr::get($responseArray, 'code') == 0) {
                $responseArray['data']['self_order_id'] = $orderId;
                return $responseArray;
            }
            if (config('baokim-payment.log.error')) {
                Log::channel(config('baokim-payment.log.chanel'))->error("Error request for orderId: #{$orderId}",
                    $responseArray);
            }
            return null;
        } catch (\Exception|GuzzleException $e) {
            if (config('baokim-payment.log.error')) {
                Log::channel(config('baokim-payment.log.chanel'))->error($e->getMessage(), $e->getTrace());
            }
            return null;
        }
    }

    /**
     * Check valid response data from BaoKim
     *
     * @param array $response
     * @return array
     * @throws InvalidMrcOrderIdException
     * @throws InvalidSignatureException
     * @throws UnknownPaymentMethodException
     */
    public static function checkValidData(array $response): array
    {
        $checkType = explode('_', Arr::get($response, 'order.mrc_order_id', ''));
        if (isset($checkType[0]) && isset($checkType[1])) {

            $paymentMethod = self::$paymentMethod;
            $transactionId = $checkType[1];

            if (!PaymentMethod::hasValue($paymentMethod)) {
                throw new UnknownPaymentMethodException();
            }

            if (config('baokim-payment.log.webhook')) {
                Log::channel(config('baokim-payment.log.chanel'))->notice("Response for orderId: #{$transactionId}",
                    $response);
            }

            $keyUsed = DB::table('transactions_keys')
                ->where([
                    ['transaction_id', $transactionId],
                    ['transaction_type', config("baokim-payment.jwt.{$paymentMethod}.transaction_type")]
                ])->first();

            if ($keyUsed &&
                $keyUsed->key_used &&
                self::checkSignatureWebhook(
                    $response,
                    config("baokim-payment.jwt.{$paymentMethod}.secret_key." . $keyUsed->key_used . '.secret'))
            ) {
                unset($response['sign']);
                return $response;
            }
            throw new InvalidSignatureException();
        }
        throw new InvalidMrcOrderIdException();
    }

    /**
     * @param array $webhookData
     * @param string $secret
     * @return bool
     */
    private static function checkSignatureWebhook(array $webhookData, string $secret): bool
    {
        $baoKimSign = $webhookData['sign'];
        unset($webhookData['sign']);

        $signData = json_encode($webhookData);
        $mySign   = hash_hmac('sha256', $signData, $secret);
        return $baoKimSign === $mySign;
    }
}