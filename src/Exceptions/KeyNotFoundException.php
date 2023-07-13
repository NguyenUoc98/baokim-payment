<?php

namespace Uocnv\BaokimPayment\Exceptions;

use Exception;
use ReturnTypeWillChange;
use Throwable;

class KeyNotFoundException extends Exception
{
    public function __construct($message = 'No key found', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange] public function __toString()
    {
        return $this->message;
    }
}
