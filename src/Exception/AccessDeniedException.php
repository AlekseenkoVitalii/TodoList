<?php

namespace App\Exception;

use Exception;
use Throwable;

class AccessDeniedException extends Exception
{
    public function __construct($message = "You do not have permission to access this task", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}