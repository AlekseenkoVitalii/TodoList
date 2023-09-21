<?php

namespace App\Exception;

use Exception;
use Throwable;

class AccessDeniedException extends Exception
{
    public function __construct($message = "You do not have permission to access this task", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}