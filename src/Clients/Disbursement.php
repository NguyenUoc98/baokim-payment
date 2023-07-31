<?php

namespace Uocnv\BaokimPayment\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Uocnv\BaokimPayment\Enums\DisbursementResponseCode;
use Uocnv\BaokimPayment\Exceptions\CollectionRequestException;
use Uocnv\BaokimPayment\Exceptions\SignFailedException;

class Disbursement extends RSAClient
{
    protected string $partnerCode;
    private string   $logChanel;
    private bool     $logRequest;
    private string   $logError;

    public const BANK_TRANSFER_ASSISTANCE = [
        970423 => [
            'name' => 'Ngân hàng TMCP Tiên Phong (TPBank)',
            'acc'  => true,
            'card' => true,
        ],
        970437 => [
            'name' => 'Ngân hàng TMCP Phát triển TP.Hồ Chí Minh (HDBank)',
            'acc'  => true,
            'card' => true,
        ],
        970408 => [
            'name' => 'Ngân hàng Thương mại TNHH MTV Dầu Khí Toàn Cầu (GPBank)',
            'acc'  => true,
            'card' => true,
        ],
        970407 => [
            'name' => 'Ngân hàng TMCP Kỹ Thương (Techcombank)',
            'acc'  => true,
            'card' => true,
        ],
        970442 => [
            'name' => 'Ngân hàng TNHH MTV Hongleong Việt Nam',
            'acc'  => true,
            'card' => true,
        ],
        970414 => [
            'name' => 'Ngân hàng TMCP Đại Dương (OceanBank)',
            'acc'  => true,
            'card' => true,
        ],
        970438 => [
            'name' => 'Ngân hàng Bảo Việt (BAOVIET Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970422 => [
            'name' => 'Ngân hàng Quân Đội (MB Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970432 => [
            'name' => 'Ngân hàng TMCP Việt Nam Thịnh Vượng (VP Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970439 => [
            'name' => 'Ngân hàng TNHH Một Thành Viên Public Việt Nam (PBVN)',
            'acc'  => true,
            'card' => true,
        ],
        970415 => [
            'name' => 'Ngân hàng TMCP Công Thương Việt Nam (Viettinbank)',
            'acc'  => true,
            'card' => true,
        ],
        970431 => [
            'name' => 'Ngân hàng TMCP Xuất Nhập khẩu Việt Nam (Eximbank)',
            'acc'  => true,
            'card' => true,
        ],
        970440 => [
            'name' => 'Ngân hàng TMCP Đông Nam Á (SeABank)',
            'acc'  => true,
            'card' => true,
        ],
        970429 => [
            'name' => 'Ngân hàng TMCP Sài Gòn (SCB)',
            'acc'  => true,
            'card' => true,
        ],
        970448 => [
            'name' => 'Ngân hàng TMCP Phương Đông (OCB)',
            'acc'  => true,
            'card' => true,
        ],
        970425 => [
            'name' => 'Ngân hàng TMCP An Bình (ABBANK)',
            'acc'  => true,
            'card' => true,
        ],
        970426 => [
            'name' => 'Ngân hàng TMCP Hàng Hải (MSB)',
            'acc'  => true,
            'card' => true,
        ],
        970427 => [
            'name' => 'Ngân hàng TMCP Việt Á (VietABank)',
            'acc'  => true,
            'card' => true,
        ],
        970419 => [
            'name' => 'Ngân hàng TMCP Quốc Dân (NCB)',
            'acc'  => true,
            'card' => true,
        ],
        970418 => [
            'name' => 'Ngân hàng Đầu tư và Phát triển Việt Nam (BIDV)',
            'acc'  => true,
            'card' => true,
        ],
        970443 => [
            'name' => 'Ngân hàng TMCP Sài Gòn - Hà Nội (SHB)',
            'acc'  => true,
            'card' => true,
        ],
        970406 => [
            'name' => 'Ngân hàng TMCP Đông Á (DongA Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970441 => [
            'name' => 'Ngân hàng TMCP Quốc Tế Việt Nam (VIB)',
            'acc'  => true,
            'card' => true,
        ],
        970424 => [
            'name' => 'Ngân hàng TNHH Một Thành Viên Shinhan Việt Nam (SHBVN)',
            'acc'  => true,
            'card' => true,
        ],
        970433 => [
            'name' => 'Ngân hàng TMCP Việt Nam Thương Tín (Vietbank)',
            'acc'  => true,
            'card' => true,
        ],
        970454 => [
            'name' => 'Ngân hàng TMCP Bản Việt (Viet Capital Bank)',
            'acc'  => true,
            'card' => false,
        ],
        970452 => [
            'name' => 'Ngân hàng TMCP Kiên Long (Kienlongbank)',
            'acc'  => true,
            'card' => true,
        ],
        970430 => [
            'name' => 'Ngân hàng TMCP Xăng dầu Petrolimex (PG Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970400 => [
            'name' => 'Ngân hàng TMCP Sài Gòn Công Thương (SAIGONBANK)',
            'acc'  => true,
            'card' => true,
        ],
        970405 => [
            'name' => 'Ngân hàng Nông nghiệp và Phát triển Nông thôn Việt Nam (Agribank)',
            'acc'  => true,
            'card' => true,
        ],
        970403 => [
            'name' => 'Ngân hàng TMCP Sài Gòn Thương Tín (Sacombank)',
            'acc'  => true,
            'card' => true,
        ],
        970412 => [
            'name' => 'Ngân hàng TMCP Đại Chúng Việt Nam (PVcomBank)',
            'acc'  => true,
            'card' => true,
        ],
        970421 => [
            'name' => 'Ngân hàng liên doanh Việt Nga (VRB)',
            'acc'  => true,
            'card' => true,
        ],
        970428 => [
            'name' => 'Ngân hàng TMCP Nam Á (Nam A Bank)',
            'acc'  => true,
            'card' => true,
        ],
        970434 => [
            'name' => 'Ngân hàng TNHH Indovina (IVB)',
            'acc'  => true,
            'card' => true,
        ],
        970449 => [
            'name' => 'Ngân hàng TMCP Bưu Điện Liên Việt (LienVietPostBank)',
            'acc'  => true,
            'card' => true,
        ],
        970457 => [
            'name' => 'Ngân hàng Woori Bank Việt Nam (Woori)',
            'acc'  => true,
            'card' => false,
        ],
        970436 => [
            'name' => 'Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank)',
            'acc'  => true,
            'card' => true,
        ],
        970416 => [
            'name' => 'Ngân hàng TMCP Á Châu (ACB)',
            'acc'  => true,
            'card' => false,
        ],
        970458 => [
            'name' => 'Ngân hàng UOB Việt Nam (UOB)',
            'acc'  => true,
            'card' => true,
        ],
        970446 => [
            'name' => 'Ngân hàng Hợp Tác Xã Việt Nam (Co-opBank)',
            'acc'  => false,
            'card' => true,
        ],
        970455 => [
            'name' => 'Ngân hàng Công nghiệp Hàn Quốc - Chi nhánh Hà Nội (SGBank)',
            'acc'  => true,
            'card' => false,
        ],
        970409 => [
            'name' => 'Ngân hàng TMCP Bắc Á (Bac A Bank)',
            'acc'  => true,
            'card' => true,
        ],
        422589 => [
            'name' => 'Ngân hàng CIMB Việt Nam (CIMB)',
            'acc'  => true,
            'card' => true,
        ],
        796500 => [
            'name' => 'Ngân hàng DBS - Chi nhánh Hồ Chí Minh(DBS)',
            'acc'  => true,
            'card' => false,
        ],
        458761 => [
            'name' => 'Ngân hàng HSBC Việt Nam (HSBC)',
            'acc'  => true,
            'card' => false,
        ],
        970410 => [
            'name' => 'Ngaanh hàng TNHH MTV Standard Chartered Việt Nam (SCVN)',
            'acc'  => true,
            'card' => false,
        ],
        801011 => [
            'name' => 'Ngân hàng Nonghuyp - Chi nhánh Hà Nội (NHB)',
            'acc'  => true,
            'card' => false,
        ],
    ];

    public function __construct($mode = 'development')
    {
        $env = in_array($mode, ['development', 'production']) ? $mode : config('baokim-payment.disbursement.environment');

        $this->partnerCode = config("baokim-payment.disbursement.{$env}.partner_code");
        $this->logChanel   = config('baokim-payment.log.chanel');
        $this->logRequest  = config('baokim-payment.log.request');
        $this->logError    = config('baokim-payment.log.error');

        parent::__construct(
            config("baokim-payment.disbursement.{$env}.private_key"),
            config("baokim-payment.disbursement.{$env}.bk_public_key"),
            config("baokim-payment.disbursement.{$env}.url")
        );
    }

    /**
     * Verify customer information
     *
     * @param string $accNo Account number or bank card number of the customer.
     * @param int $bankNo Bank code in accordance with Baokim is defined in the section 8. List of remittance banks
     * @param int $accType AccNo classification 0: Bank account number 1: Bank card number
     * @param int $operation This parameter will determine which function that partner is calling. For customer authentication functions, the fix is “9001”
     * @param string|null $requestTime It is time to send request from Partner, format: YYYY-MM-DD HH:MM:SS
     * @param string|null $requestId The only code that corresponds to an upload request. Proposed format is as follows: PartnerCode + BK + YYYYMMDD + UniqueId
     * @param string|null $partnerCode The partner code is defined in the Baokim system. This code will send to the partner when the integration begins.
     * @return mixed
     * @throws CollectionRequestException
     * @throws SignFailedException
     * @throws GuzzleException
     */
    public function verifyCustomerInfo(
        string $accNo,
        int $bankNo,
        int $accType = 0,
        int $operation = 9001,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        $dataPost = [
            'RequestId'   => $requestId,
            'RequestTime' => $requestTime,
            'PartnerCode' => $partnerCode,
            'Operation'   => $operation,
            'BankNo'      => $bankNo,
            'AccNo'       => $accNo,
            'AccType'     => $accType
        ];

        if ($this->logRequest) {
            Log::channel($this->logChanel)->info("Request verify acc #{$requestId}", $dataPost);
        }

        $signature = $this->makeSignature(
            data     : $dataPost,
            structure: "RequestId|RequestTime|PartnerCode|Operation|BankNo|AccNo|AccType"
        );

        $dataPost['Signature'] = $signature;

        $client   = new Client();
        $response = $client->post($this->url, ["json" => $dataPost]);

        if ($response->getStatusCode() == 200) {
            $response_txt = $response->getBody()->getContents();
            return json_decode($response_txt, true);
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when request verify acc #{$accNo}");
        }

        throw new CollectionRequestException($response->getStatusCode(), 'Error from BaoKim');
    }

    /**
     * Transfer money
     *
     * @param int $requestAmount The amount requested by the partner to transfer to the recipient.
     * @param string $referenceId Transaction code sent by the partner
     * @param string $memo Money transfer contents
     * @param string $accNo Account number or bank card number of the customer.
     * @param int $bankNo Bank code in accordance with Baokim is defined in the section 8. List of remittance banks
     * @param int $accType AccNo classification 0: Bank account number 1: Bank card number
     * @param int $operation This parameter will determine which function partner is calling. For the transfer function, the fix is “9002”
     * @param string|null $requestTime It is time to send request from Partner, format: YYYY-MM-DD HH:MM:SS
     * @param string|null $requestId The only code that corresponds to an upload request. Proposed format is as follows: PartnerCode + BK + YYYYMMDD + UniqueId
     * @param string|null $partnerCode The partner code is defined in the Baokim system. This code will send to the partner when the integration begins.
     * @return mixed
     * @throws CollectionRequestException
     * @throws GuzzleException
     * @throws SignFailedException
     */
    public function transferMoney(
        int $requestAmount,
        string $referenceId,
        string $memo,
        string $accNo,
        int $bankNo,
        int $accType = 0,
        int $operation = 9002,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? mt_rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        // Chuẩn hóa dữ liệu
        $accNo = preg_replace('/\D/', '', $accNo);

        $dataPost = [
            'RequestId'     => $requestId,
            'RequestTime'   => $requestTime,
            'PartnerCode'   => $partnerCode,
            'Operation'     => $operation,
            'ReferenceId'   => $referenceId,
            'BankNo'        => $bankNo,
            'AccNo'         => $accNo,
            'AccType'       => $accType,
            'RequestAmount' => $requestAmount,
            'Memo'          => $memo,
        ];

        $signature             = $this->makeSignature(
            data     : $dataPost,
            structure: "RequestId|RequestTime|PartnerCode|Operation|ReferenceId|BankNo|AccNo|AccType|RequestAmount|Memo"
        );
        $dataPost['Signature'] = $signature;

        $client       = new Client();
        $response_txt = ($client->post($this->url, ["json" => $dataPost]))->getBody()->getContents();
        $response     = json_decode($response_txt, true);
        if ($response["ResponseCode"] == DisbursementResponseCode::SUCCESSFUL) {
            return $response;
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when transfer money #{$accNo}", $response);
        }

        throw new CollectionRequestException(json_encode([$dataPost, $response]), $response["ResponseCode"]);
    }

    /**
     * Look up for transfer info
     *
     * @param string $referenceId Transaction code from PARTNER submitted
     * @param int $operation This parameter will determine which function partner is calling. For transactional lookup information, the fix is "9003
     * @param string|null $requestTime It is time to send request from Partner, format: YYYY-MM-DD HH:MM:SS
     * @param string|null $requestId The only code that corresponds to an upload request. Proposed format is as follows: PartnerCode + BK + YYYYMMDD + UniqueId
     * @param string|null $partnerCode The partner code is defined in the Baokim system. This code will send to the partner when the integration begins.
     * @return mixed
     * @throws CollectionRequestException
     * @throws GuzzleException
     * @throws SignFailedException
     */
    public function lookUpForTransferInfo(
        string $referenceId,
        int $operation = 9003,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? mt_rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        $dataPost = [
            'RequestId'   => $requestId,
            'RequestTime' => $requestTime,
            'PartnerCode' => $partnerCode,
            'Operation'   => $operation,
            'ReferenceId' => $referenceId
        ];

        $signature             = $this->makeSignature(
            data     : $dataPost,
            structure: "RequestId|RequestTime|PartnerCode|Operation|ReferenceId"
        );
        $dataPost['Signature'] = $signature;

        $client   = new Client();
        $response = $client->post($this->url, ["json" => $dataPost]);

        if ($response->getStatusCode() == 200) {
            $response_txt = $response->getBody()->getContents();
            return json_decode($response_txt, true);
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when look up for transfer info #{$requestId}");
        }

        throw new CollectionRequestException($response->getStatusCode(), 'Error from BaoKim');
    }

    /**
     * Look up for Partner balance
     *
     * @param int $operation This parameter will determine which function partner is calling. For lookup balance information, the fix is (9004)
     * @param string|null $requestTime It is time to send request from Partner, format: YYYY-MM-DD HH:MM:SS
     * @param string|null $requestId The only code that corresponds to an upload request. Proposed format is as follows: PartnerCode + BK + YYYYMMDD + UniqueId
     * @param string|null $partnerCode The partner code is defined in the Baokim system. This code will send to the partner when the integration begins.
     * @return mixed
     * @throws CollectionRequestException
     * @throws GuzzleException
     * @throws SignFailedException
     */
    public function lookUpForBalance(
        int $operation = 9004,
        string $requestTime = null,
        string $requestId = null,
        string $partnerCode = null
    ): mixed {
        $partnerCode = $partnerCode ?? $this->partnerCode;
        $requestId   = "{$partnerCode}BK" . date("Ymd") . ($requestId ?? mt_rand());
        $requestTime = $requestTime ?? date("Y-m-d H:i:s");

        $dataPost = [
            'RequestId'   => $requestId,
            'RequestTime' => $requestTime,
            'PartnerCode' => $partnerCode,
            'Operation'   => $operation
        ];

        $signature             = $this->makeSignature(
            data     : $dataPost,
            structure: "RequestId|RequestTime|PartnerCode|Operation"
        );
        $dataPost['Signature'] = $signature;

        $client   = new Client();
        $response = $client->post($this->url, ["json" => $dataPost]);

        if ($response->getStatusCode() == 200) {
            $response_txt = $response->getBody()->getContents();
            return json_decode($response_txt, true);
        }

        if ($this->logError) {
            Log::channel($this->logChanel)->error("Error when look up for balance #{$requestId}");
        }

        throw new CollectionRequestException($response->getStatusCode(), 'Error from BaoKim');
    }
}