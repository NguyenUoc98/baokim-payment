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
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\PaymentMethod;
use Uocnv\BaokimPayment\Lib\BaoKimJWT;

class ATM extends JWTClient
{
    public static function request(
        int $transactionId,
        int $amount,
        string $referer,
        int $bankId = 0,
        string $userEmail = '',
        string $userPhone = ''
    ): ?array {
        self::$paymentMethod = PaymentMethod::ATM;
        return parent::request($transactionId, $amount, $referer, $bankId, $userEmail, $userPhone);
    }

    public static function checkValidData(array $response): array
    {
        self::$paymentMethod = PaymentMethod::ATM;
        return parent::checkValidData($response);
    }

    /**
     * Get list bank from Bao Kim
     *
     * @return array
     * @throws GuzzleException
     */
    public static function getBankList(): array
    {
        try {
            $jwt      = BaoKimJWT::refreshToken(PaymentMethod::ATM);
            $client   = JWTClient::makeClient();
            $response = $client->get(config('baokim-payment.jwt.atm.uri_bank_list') . '?jwt=' . $jwt);
            unset($client);
            if ($response->getStatusCode() == 200) {
                $responseTxt   = $response->getBody()->getContents();
                $responseArray = json_decode($responseTxt, true);
                return Arr::where($responseArray['data'], function ($value) {
                    return $value['type'] == 1;
                });
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