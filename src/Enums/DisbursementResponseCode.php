<?php

namespace Uocnv\BaokimPayment\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESSFUL()
 * @method static static TRANSACTION_TIMEOUT()
 * @method static static FAILED()
 * @method static static ERROR_PROCESSING_FROM_BAOKIM()
 * @method static static DUPLICATED_REQUEST_ID()
 * @method static static INCORRECT_SIGNATURE()
 * @method static static INCORRECT_PARTNER_CODE()
 * @method static static PARTNER_CODE_DELETED_FROM_SYSTEM()
 * @method static static PARTNER_CODE_NOT_YET_ACTIVATED()
 * @method static static OPERATION_CODE_IS_REQUIRED()
 * @method static static INCORRECT_OPERATION_CODE()
 * @method static static BANK_ID_IS_REQUIRED()
 * @method static static BANK_ID_NOT_SUPPORTED()
 * @method static static ACC_NO_BEYOND_LENGTH()
 * @method static static INVALID_ACC_NO()
 * @method static static ACC_NO_DOES_NOT_EXIST()
 * @method static static INVALID_ACC_TYPE()
 * @method static static TRANS_ID_FROM_PARTNER_IS_REQUIRED()
 * @method static static TRANS_ID_BY_PARTNER_IS_EXISTING()
 * @method static static TRANSACTION_UNFOUND()
 * @method static static TRANSFER_AMOUNT_REQUIRED()
 * @method static static INVALID_TRANSFER_AMOUNT()
 * @method static static ERROR_PROCESSING_BETWEEN_BK_AND_BANK()
 * @method static static ERROR_CONNECTING_TO_BANK()
 * @method static static ERROR_PROCESSING_FROM_BANK()
 * @method static static INSUFFICIENT_DISBURSEMENT_LIMIT()
 * @method static static TRANSFER_LIMIT_ON_DAY()
 */
final class DisbursementResponseCode extends Enum
{
    public const SUCCESSFUL                           = 200;
    public const TRANSACTION_TIMEOUT                  = 99;
    public const FAILED                               = 11;
    public const ERROR_PROCESSING_FROM_BAOKIM         = 101;
    public const DUPLICATED_REQUEST_ID                = 102;
    public const INCORRECT_SIGNATURE                  = 103;
    public const INCORRECT_PARTNER_CODE               = 110;
    public const PARTNER_CODE_DELETED_FROM_SYSTEM     = 111;
    public const PARTNER_CODE_NOT_YET_ACTIVATED       = 112;
    public const OPERATION_CODE_IS_REQUIRED           = 113;
    public const INCORRECT_OPERATION_CODE             = 114;
    public const BANK_ID_IS_REQUIRED                  = 115;
    public const BANK_ID_NOT_SUPPORTED                = 116;
    public const ACC_NO_BEYOND_LENGTH                 = 117;
    public const INVALID_ACC_NO                       = 118;
    public const ACC_NO_DOES_NOT_EXIST                = 119;
    public const INVALID_ACC_TYPE                     = 120;
    public const TRANS_ID_FROM_PARTNER_IS_REQUIRED    = 121;
    public const TRANS_ID_BY_PARTNER_IS_EXISTING      = 122;
    public const TRANSACTION_UNFOUND                  = 123;
    public const TRANSFER_AMOUNT_REQUIRED             = 124;
    public const INVALID_TRANSFER_AMOUNT              = 125;
    public const ERROR_PROCESSING_BETWEEN_BK_AND_BANK = 126;
    public const ERROR_CONNECTING_TO_BANK             = 127;
    public const ERROR_PROCESSING_FROM_BANK           = 128;
    public const INSUFFICIENT_DISBURSEMENT_LIMIT      = 129;
    public const TRANSFER_LIMIT_ON_DAY                = 130;
}
