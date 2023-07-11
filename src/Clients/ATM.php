<?php
/**
 * Created by PhpStorm
 * Filename: ATM.php
 * User: Nguyễn Văn Ước
 * Date: 11/07/2023
 * Time: 11:24
 */

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\PaymentMethod;
use Uocnv\BaokimPayment\Lib\BaoKimJWT;

class ATM
{
    /**
     * Create request for payment atm
     *
     * @param int $transactionId
     * @param int $amount
     * @param int $bankId
     * @param string $referer
     * @param string $userEmail
     * @param string $userPhone
     * @param string $key
     * @return array|null
     */
    public static function request(
        int $transactionId,
        int $amount,
        int $bankId,
        string $referer,
        string $userEmail = '',
        string $userPhone = '',
        string $key = ''
    ): ?array {
        $config = config('baokim-payment.jwt.' . PaymentMethod::ATM);
        $uri    = Arr::get($config, 'uri_api');

        if (!$key) {
            $key = Arr::first(array_keys(Arr::get($config, 'secret_key')));
        }

        $postData = [
            'amount'     => $amount,
            'bank_id'    => $bankId,
            'referer'    => $referer,
            'user_email' => $userEmail,
            'user_phone' => $userPhone,
        ];

        return JWTClient::request(
            uri          : $uri,
            key          : $key,
            transactionId: $transactionId,
            paymentMethod: PaymentMethod::ATM,
            data         : $postData
        );
    }

    /**
     * Get list bank from Bao Kim
     *
     * @return array
     * @throws GuzzleException
     */
    public static function getBankList(): array
    {
        if (Cache::has('__banks_baokim')) {
            return Cache::get('__banks_baokim');
        }
        try {
            $jwt      = BaoKimJWT::refreshToken(PaymentMethod::ATM);
            $client   = JWTClient::makeClient();
            $response = $client->get(config('baokim-payment.jwt.atm.uri_bank_list') . '?jwt=' . $jwt);
            unset($client);
            if ($response->getStatusCode() == 200) {
                $responseTxt   = $response->getBody()->getContents();
                $responseArray = json_decode($responseTxt, true);
                $banks         = Arr::where($responseArray['data'], function ($value) {
                    return $value['type'] == 1;
                });
                Cache::add('__banks_baokim', $banks, 86400);
                return $banks;
            }
            return [];
        } catch (\Exception $e) {
            if (config('baokim-payment.log.error')) {
                Log::channel(config('baokim-payment.log.chanel'))->error($e->getMessage(), $e->getTrace());
            }
            return [];
        }
    }
}