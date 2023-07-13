<?php

namespace Uocnv\BaokimPayment\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ATM()
 * @method static static MOMO()
 * @method static static MOBILE_CARD()
 */
final class PaymentMethod extends Enum
{
    public const ATM    = 'atm';
    public const MOMO   = 'momo';
    public const MOBILE = 'mobile';
}