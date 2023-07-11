<?php

namespace Uocnv\BaokimPayment\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ATM()
 * @method static static MOMO()
 */
final class PaymentMethod extends Enum
{
    public const ATM  = 'atm';
    public const MOMO = 'momo';
}