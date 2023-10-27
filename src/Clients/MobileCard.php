<?php
/**
 * Created by PhpStorm
 * Filename: MobileCard.php
 * User: Nguyễn Văn Ước
 * Date: 12/07/2023
 * Time: 14:52
 */

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\PaymentMethod;
use Uocnv\BaokimPayment\Enums\TelecomOperator;
use Uocnv\BaokimPayment\Exceptions\InvalidMrcOrderIdException;
use Uocnv\BaokimPayment\Exceptions\InvalidSignatureException;
use Uocnv\BaokimPayment\Exceptions\UnknownPaymentMethodException;
use Uocnv\BaokimPayment\Lib\BaoKimJWT;
use Uocnv\BaokimPayment\Lib\Helper;

class MobileCard
{
    /**
     * Request create order
     *
     * @param int $transactionId
     * @param int $amount
     * @param string $pin
     * @param string $serial
     * @param int $telecomOperator
     * @param string $webhook
     * @return mixed|null
     */
    public static function request(
        int $transactionId,
        int $amount,
        string $pin,
        string $serial,
        int $telecomOperator,
        string $webhook = ''
    ): mixed {
        if (!TelecomOperator::hasValue($telecomOperator)) {
            return null;
        }

        $orderId  = config('baokim-payment.jwt.key', '123doc') . '.' . PaymentMethod::MOBILE . '_' . $transactionId;
        $arrayKey = config('baokim-payment.jwt.mobile.secret_key');
        $key      = Helper::getRandomWeight($arrayKey) ?: Arr::first(array_keys($arrayKey));

        try {
            DB::table('transactions_keys')->insert([
                'key_used'         => $key,
                'transaction_id'   => $transactionId,
                'transaction_type' => config('baokim-payment.jwt.' . PaymentMethod::MOBILE . '.transaction_type'),
            ]);

            $postArgs = array(
                'mrc_order_id' => $orderId,
                'telco'        => TelecomOperator::fromValue($telecomOperator)->description,
                'amount'       => $amount,
                'code'         => $pin,
                'serial'       => $serial,
                'webhooks'     => $webhook ?: config('baokim-payment.jwt.mobile.webhook'),
            );


            if (config('baokim-payment.log.request')) {
                Log::channel(config('baokim-payment.log.chanel'))->info("Request for orderId: #{$orderId}", $postArgs);
            }

            BaoKimJWT::$dataPost = $postArgs;
            $jwt                 = BaoKimJWT::refreshToken(PaymentMethod::MOBILE, $key);
            $urlApi              = config('baokim-payment.jwt.mobile.uri_api');
            $client              = new Client([
                'headers' => [
                    'Content-Type' => "application/json",
                    'Accept'       => "application/json",
                ],
            ]);

            $response = $client->post("{$urlApi}?jwt={$jwt}", [
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
        JWTClient::$paymentMethod = PaymentMethod::MOBILE;
        return JWTClient::checkValidData($response);
    }
}