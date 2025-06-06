<?php

namespace Uocnv\BaokimPayment\Exceptions;

use Exception;
use ReturnTypeWillChange;
use Throwable;

class UnknownPaymentMethodException extends Exception
{
    public function __construct($message = 'Unknown payment method', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange] public function __toString()
    {
        return $this->message;
    }
}
