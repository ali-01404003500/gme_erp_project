<?php

namespace App\Exceptions;

use Throwable;

class IntegrityAvailableException extends \Exception //IntegrityAvailableException
{
    
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}