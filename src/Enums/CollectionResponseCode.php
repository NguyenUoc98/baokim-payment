<?php

namespace Uocnv\BaokimPayment\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUCCESSFUL()
 * @method static static TRANSACTION_TIMEOUT()
 * @method static static FAILED()
 * @method static static ERROR_PROCESSING_FROM_BAOKIM()
 * @method static static ERROR_FROM_BANK()
 * @method static static OPERATION_IS_INCORRECT()
 * @method static static REQUESTID_OR_REQUEST_IS_INCORRECT()
 * @method static static PARTNERCODE_IS_INCORRECT()
 * @method static static ACCNAME_IS_INCORRECT()
 * @method static static CLIENTIDNO_IS_INCORRECT()
 * @method static static ISSUEDDATE_OR_ISSUEDPLACE_IS_INCORRECT()
 * @method static static COLLECTAMOUNT_IS_INCORRECT()
 * @method static static EXPIREDATE_IS_INCORRECT()
 * @method static static ACCNO_IS_INCORRECT()
 * @method static static ACCNO_IS_NOT_EXIST()
 * @method static static REFFERENCEID_IS_INCORRECT()
 * @method static static REFFERENCEID_ISNT_EXISTS()
 * @method static static TRANSAMOUNT_IS_INCORRECT()
 * @method static static TRANSTIME_IS_INCORRECT()
 * @method static static BEFTRANSDEBT_IS_INCORRECT()
 * @method static static TRANSID_IS_INCORRECT()
 * @method static static AFFTRANSDEBT_IS_INCORRECT()
 * @method static static SIGNATURE_IS_INCORRECT()
 * @method static static ACCOUNTTYPE_IS_INCORRECT()
 * @method static static ORDERID_IS_INCORRECT()
 */
final class CollectionResponseCode extends Enum
{
    public const SUCCESSFUL                             = 200;
    public const TRANSACTION_TIMEOUT                    = 99;
    public const FAILED                                 = 11;
    public const ERROR_PROCESSING_FROM_BAOKIM           = 101;
    public const ERROR_FROM_BANK                        = 102;
    public const OPERATION_IS_INCORRECT                 = 103;
    public const REQUESTID_OR_REQUEST_IS_INCORRECT      = 104;
    public const PARTNERCODE_IS_INCORRECT               = 105;
    public const ACCNAME_IS_INCORRECT                   = 106;
    public const CLIENTIDNO_IS_INCORRECT                = 107;
    public const ISSUEDDATE_OR_ISSUEDPLACE_IS_INCORRECT = 108;
    public const COLLECTAMOUNT_IS_INCORRECT             = 109;
    public const EXPIREDATE_IS_INCORRECT                = 110;
    public const ACCNO_IS_INCORRECT                     = 111;
    public const ACCNO_IS_NOT_EXIST                     = 112;
    public const REFFERENCEID_IS_INCORRECT              = 113;
    public const REFFERENCEID_ISNT_EXISTS               = 114;
    public const TRANSAMOUNT_IS_INCORRECT               = 114;
    public const TRANSTIME_IS_INCORRECT                 = 116;
    public const BEFTRANSDEBT_IS_INCORRECT              = 117;
    public const TRANSID_IS_INCORRECT                   = 118;
    public const AFFTRANSDEBT_IS_INCORRECT              = 119;
    public const SIGNATURE_IS_INCORRECT                 = 120;
    public const ACCOUNTTYPE_IS_INCORRECT               = 121;
    public const ORDERID_IS_INCORRECT                   = 122;
}