<?php

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Client;
use Uocnv\BaokimPayment\Exceptions\KeyNotFoundException;
use Uocnv\BaokimPayment\Exceptions\SignFailedException;
use Uocnv\BaokimPayment\Lib\RSA;

class RSAClient
{
    /**
     * @var RSA
     */
    protected RSA $encrypter;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @throws KeyNotFoundException
     */
    public function __construct($private_key, $public_key, $url)
    {
        $this->encrypter = new RSA($private_key, $public_key);
        $this->url       = $url;
    }

    /**
     * @param array $data data will be signed
     * @param string $separator
     * @param null $structure structure of signature
     * @return string signature
     * @throws SignFailedException
     */
    protected function makeSignature(array $data, string $separator = "|", $structure = null): string
    {
        if ($structure) {
            $data = array_filter($data, function ($k) use ($structure) {
                return in_array($k, explode("|", $structure));
            }, ARRAY_FILTER_USE_KEY);
        }
        if ($separator == "json") {
            return $this->encrypter->sign(json_encode($data));
        }
        return $this->encrypter->sign(implode($separator, $data));
    }

    /**
     * @param string $signature
     * @return Client
     */
    protected function makeClient(string $signature): Client
    {
        return new Client([
            'headers'  => [
                'Content-Type' => "application/json",
                'Signature'    => $signature,
            ],
            'base_uri' => $this->url
        ]);
    }
}