<?php
/**
 * Created by PhpStorm
 * Filename: Momo.php
 * User: Nguyễn Văn Ước
 * Date: 11/07/2023
 * Time: 17:23
 */

namespace Uocnv\BaokimPayment\Clients;

use Uocnv\BaokimPayment\Enums\PaymentMethod;

class Momo extends JWTClient
{
    public static function request(
        int $transactionId,
        int $amount,
        string $referer,
        int $bankId = 0,
        string $userEmail = '',
        string $userPhone = '',
        string $webhook = ''
    ): ?array {
        self::$paymentMethod = PaymentMethod::MOMO;
        return parent::request(...func_get_args());
    }

    public static function checkValidData(array $response): array
    {
        self::$paymentMethod = PaymentMethod::MOMO;
        return parent::checkValidData($response);
    }
}
