<?php

namespace Uocnv\BaokimPayment\Enums;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * @method static static VIETTEL()
 * @method static static MOBIFONE()
 * @method static static VINAPHONE()
 */
final class TelecomOperator extends Enum
{
    #[Description('VIETTEL')]
    public const VIETTEL   = 107;

    #[Description('MOBI')]
    public const MOBIFONE  = 92;

    #[Description('VINA')]
    public const VINAPHONE = 93;
}