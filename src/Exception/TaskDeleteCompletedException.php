<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskDeleteCompletedException extends Exception
{
    public function __construct($message = "Unable to delete completed task", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}