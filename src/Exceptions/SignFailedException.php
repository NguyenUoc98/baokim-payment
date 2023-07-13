<?php

namespace Uocnv\BaokimPayment\Exceptions;

use Exception;
use ReturnTypeWillChange;
use Throwable;

class SignFailedException extends Exception
{
    public function __construct($message = 'Can not sign data', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange] public function __toString()
    {
        return $this->message;
    }
}
