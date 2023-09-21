<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskCompletionException extends Exception
{
    public function __construct($message = "A task cannot be completed until there are uncompleted subtasks", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}