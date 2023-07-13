<?php

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\CollectionResponseCode;
use Uocnv\BaokimPayment\Exceptions\CollectionRequestException;
use Uocnv\BaokimPayment\Exceptions\InvalidSignatureException;
use Uocnv\BaokimPayment\Exceptions\SignFailedException;

class VA extends RSAClient
{
    protected string $partnerCode;
    private string   $logChanel;
    private bool     $logRequest;
    private bool     $logWebhook;
    private string   $logError;

    public function __construct(string $mode = 'development')
    {
        $env               = in_array($mode, ['development', 'production']) ? $mode : 'development';
        $this->partnerCode = config("baokim-payment.virtual_account.{$env}.partner_code");
        $this->logChanel   = config('baokim-payment.log.chanel');
        $this->logRequest  = config('baokim-payment.log.request');
        $this->logWebhook  = config('baokim-payment.log.webhook');
        $this->logError    = config('baokim-payment.log.error');

        parent::__construct(
            config("baokim-payment.virtual_account.{$env}.private_key"),
            config("baokim-payment.virtual_account.{$env}.public_key"),
            config("baokim-payment.virtual_account.{$env}.url")
        );
    }

    /**
     * PARTNER will call transaction by identification status checking,
     * BAOKIM will check the data format and signature authentication
     * If the information is correct, BAOKIM will cancel pending cash transfer transaction and return response to PARTNER.
     *
     * @param string $accName The name of Account holder (name of USER)
     * @param string $orderId Unique id for each VA
     * @param int $amountMin Require  Min collect amount (Min 50.000 vnd)
     * @param int $amountMax Require  Max collect amount (Max 50.000.000vnd)
     * @param int $createType Note: BK won't check this field, can send 2
     * @param int $operation Fix: 9001
     * @param string|null $requestTime Time send the request from PARTNER , format: YYYY-MM-DD HH:MM:SS.
     * @param string|null $requestId Unique code , recomment format: PartnerCode + BK + YYYYMMDD + UniqueId.
     * @param string|null $partnerCode Unique code BAOKIM provide
     * @param string|null $accNo VA number (Max 17 characters).Note: BK won't check this field, can send NULL
     * @param string|null $expireDate Expire date. Format: YYYYMM-DD HH:II:SS
     * @return mixed
     * @throws CollectionRequestException
     * @throws GuzzleException
     * @throws SignFailedException
     */
    public function registerVirtualAccount(
        string $accName,
        string $orderId,
        int $amountMin = 50000,
        int $amountMax = 50000000,
        int $createType = 2,
        int $operation = 9001,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null,
        string $accNo = null,
        string $expireDate = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        $data = [
            "RequestId"        => $requestId,
            "RequestTime"      => $requestTime,
            "PartnerCode"      => $partnerCode,
            "Operation"        => $operation,
            "CreateType"       => $createType,
            "AccName"          => $accName,
            "CollectAmountMin" => $amountMin,
            "CollectAmountMax" => $amountMax,
            "ExpireDate"       => $expireDate,
            "OrderId"          => $orderId,
            "AccNo"            => $accNo
        ];

        if ($this->logRequest) {
            Log::channel($this->logChanel)->info("Create VA #{$orderId}", $data);
        }

        $signature    = $this->makeSignature($data, 'json');
        $client       = $this->makeClient($signature);
        $response_txt = ($client->post("", ["json" => $data]))->getBody()->getContents();
        $response     = json_decode($response_txt, true);

        if ($response["ResponseCode"] == CollectionResponseCode::SUCCESSFUL) {
            return $response;
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when create VA #{$orderId}", $response);
        }

        throw new CollectionRequestException($response["ResponseMessage"], $response["ResponseCode"]);
    }

    /**
     * PARTNER wanto change and save the USER changed information , will call to “Virtual account information update”.
     * BAOKIM will check about datatype and the signature accuracy .
     * If every submitted data are correct, BAOKIM will update the virtual account by the provided data, At the same time BAOKIM will response to PARTNER.
     *
     * @param string $accName
     * @param string $accNo
     * @param string $orderId
     * @param string|null $expireDate
     * @param int $amountMin
     * @param int $amountMax
     * @param int $operation
     * @param string|null $requestTime
     * @param string|null $requestId
     * @param string|null $partnerCode
     * @return mixed
     * @throws CollectionRequestException
     * @throws GuzzleException
     * @throws SignFailedException
     */
    public function updateVirtualAccount(
        string $accName,
        string $accNo,
        string $orderId,
        string $expireDate = null,
        int $amountMin = 50000,
        int $amountMax = 50000000,
        int $operation = 9002,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        $data = [
            "RequestId"        => $requestId,
            "RequestTime"      => $requestTime,
            "PartnerCode"      => $partnerCode,
            "Operation"        => $operation,
            "AccNo"            => $accNo,
            "AccName"          => $accName,
            "CollectAmountMin" => $amountMin,
            "CollectAmountMax" => $amountMax,
            "OrderId"          => $orderId,
            "ExpireDate"       => $expireDate,
        ];

        if ($this->logRequest) {
            Log::channel($this->logChanel)->info("Update VA #{$orderId}", $data);
        }

        $signature    = $this->makeSignature($data, "json");
        $client       = $this->makeClient($signature);
        $response_txt = ($client->post("", ["json" => $data]))->getBody()->getContents();
        $response     = json_decode($response_txt, true);

        if ($response["ResponseCode"] == CollectionResponseCode::SUCCESSFUL) {
            return $response;
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when update VA #{$orderId}", $response);
        }

        throw new CollectionRequestException($response["ResponseMessage"], $response["ResponseCode"]);
    }

    /**
     * PARTNER build the system, to receive data notice the collection transaction.
     * When receive a new collection transaction, BAOKIM will call to “collection transaction notification” that provided by PARTNER to notice PARTNER need to update data.
     *
     * @param string $response
     * @return mixed
     * @throws InvalidSignatureException
     */
    public function checkValidData(string $response): mixed
    {
        $data    = json_decode($response, true);
        $orderId = $data['AccNo'];

        if ($this->logWebhook) {
            Log::channel($this->logChanel)->notice("Response for VA #{$orderId}", $data);
        }

        $signature = base64_decode($data['Signature']);
        unset($data['Signature']);

        $dataSign = implode('|', $data);
        if ($this->encrypter->verify($dataSign, $signature)) {
            return $data;
        }

        throw new InvalidSignatureException();
    }

    /**
     * Create response for BaoKim
     *
     * @param array $data
     * @return false|string
     * @throws SignFailedException
     */
    public function makeResponse(array $data): bool|string
    {
        $signature         = base64_encode($this->makeSignature(
            data     : $data,
            structure: "ResponseCode|ResponseMessage|ReferenceId|AccNo|AffTransDebt"
        ));
        $data['Signature'] = $signature;

        return json_encode($data);
    }
}