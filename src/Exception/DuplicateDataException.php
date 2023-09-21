<?php

namespace App\Exception;

use Exception;
use Throwable;

class DuplicateDataException extends Exception
{
    public function __construct($message = "This email is already registered", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}