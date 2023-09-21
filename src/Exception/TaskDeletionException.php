<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskDeletionException extends Exception
{
    public function __construct($message = "There is no way to delete a task that has subtasks", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}