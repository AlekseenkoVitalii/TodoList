<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskDeleteCompletedException extends Exception
{
    public function __construct($message = "Unable to delete completed task", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}