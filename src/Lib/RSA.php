<?php
/**
 * Created by PhpStorm
 * Filename: RSA.php
 * User: Nguyễn Văn Ước
 * Date: 13/07/2023
 * Time: 10:32
 */

namespace Uocnv\BaokimPayment\Lib;

use Uocnv\BaokimPayment\Exceptions\InvalidSignatureException;
use Uocnv\BaokimPayment\Exceptions\KeyNotFoundException;
use Uocnv\BaokimPayment\Exceptions\SignFailedException;

class RSA
{
    /**
     * @var string
     */
    protected string $publicKey;

    /**
     * @var string
     */
    protected string $privateKey;

    /**
     * RSA constructor.
     * @param $privateKey
     * @param $publicKey
     * @throws KeyNotFoundException
     */
    public function __construct($privateKey, $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;
        if (!$this->publicKey) {
            throw new KeyNotFoundException("No public key found");
        }
        if (!$this->privateKey) {
            throw new KeyNotFoundException("No private key found");
        }
    }

    /**
     * @param $data
     * @param int $algorithm
     * @return string
     * @throws SignFailedException
     */
    public function sign($data, int $algorithm = OPENSSL_ALGO_SHA1): string
    {
        if (openssl_sign($data, $encrypted, $this->privateKey, $algorithm)) {
            return base64_encode($encrypted);
        }
        throw new SignFailedException();
    }

    /**
     * @param string $data
     * @param $signature
     * @param int $algorithm
     * @return bool|string
     * @throws InvalidSignatureException
     */
    public function verify(string $data, $signature, int $algorithm = OPENSSL_ALGO_SHA1): bool|string
    {
        $ok = openssl_verify($data, $signature, $this->publicKey, $algorithm);
        return match ($ok) {
            1       => true,
            0       => false,
            default => throw new InvalidSignatureException("Error checking signature"),
        };
    }
}