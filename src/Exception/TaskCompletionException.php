<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskCompletionException extends Exception
{
    public function __construct($message = "A task cannot be completed until there are uncompleted subtasks", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}