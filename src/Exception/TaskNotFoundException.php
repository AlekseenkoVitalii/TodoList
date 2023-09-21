<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskNotFoundException extends Exception
{
    public function __construct($message = "Task not found", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}