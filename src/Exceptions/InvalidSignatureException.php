<?php

namespace Uocnv\BaokimPayment\Exceptions;

use Exception;
use ReturnTypeWillChange;
use Throwable;

class InvalidSignatureException extends Exception
{
    public function __construct($message = 'Forbidden: Failed to verify signature. You don’t have permission to access this!', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange] public function __toString()
    {
        return $this->message;
    }
}
