<?php
/**
 * Created by PhpStorm
 * Filename: BaoKimJWT.php
 * User: Nguyễn Văn Ước
 * Date: 10/07/2023
 * Time: 14:34
 */

namespace Uocnv\BaokimPayment\Lib;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BaoKimJWT
{
    const TOKEN_EXPIRE = 500; //token expire time in seconds

    private static $_jwt     = null;
    public static  $dataPost = null;

    /**
     * Refresh JWT
     *
     * @param string $paymentMethod
     * @param string|null $key
     * @return string|null
     * @throws Exception
     */
    public static function refreshToken(string $paymentMethod, ?string $key = null): ?string
    {
        $tokenId   = base64_encode(random_bytes(8));
        $issuedAt  = time();
        $notBefore = $issuedAt;
        $expire    = $notBefore + self::TOKEN_EXPIRE;
        $wallet    = self::getApiSecret($paymentMethod, $key);

        /*
         * Create the token as an array
         */
        $data = array(
            'iat'         => $issuedAt,         // Issued at: time when the token was generated
            'jti'         => $tokenId,          // Json Token Id: an unique identifier for the token
            'iss'         => $wallet["key"],    // Issuer
            'nbf'         => $notBefore,        // Not before
            'exp'         => $expire,           // Expire
            'data'        => array(),           // Data related to the signer user
            'form_params' => self::$dataPost
        );


        /*
         * Encode the array to a JWT string.
         * Second parameter is the key to encode the token.
         *
         * The output string can be validated at http://jwt.io/
         */
        self::$_jwt = JWT::encode(
            $data,              // Data to be encoded in the JWT
            $wallet["secret"],  // The signing key
            'HS256'
        );

        return self::$_jwt;
    }

    /**
     * Get JWT
     *
     * @param string $paymentMethod
     * @param string|null $key
     * @return null
     * @throws Exception
     */
    public static function getToken(string $paymentMethod, ?string $key = null)
    {
        $wallet = self::getApiSecret($paymentMethod, $key);
        if (!self::$_jwt) {
            self::refreshToken($paymentMethod, $key);
        }

        try {
            JWT::decode(self::$_jwt, new Key($wallet['secret'], 'HS256'));
        } catch (Exception) {
            self::refreshToken($paymentMethod, $key);
        }

        return self::$_jwt;
    }

    /**
     * @param string $paymentMethod
     * @param string|null $key
     * @return array
     */
    private static function getApiSecret(string $paymentMethod, ?string $key): array
    {
        return $key ? config("baokim-payment.jwt.{$paymentMethod}.secret_key.{$key}") : config('baokim-payment.jwt.atm.secret_key.123doc_key');
    }
}