<?php

namespace App\Exception;

use Exception;
use Throwable;

class TaskDeletionException extends Exception
{
    public function __construct($message = "There is no way to delete a task that has subtasks", Throwable $previous = null)
    {
        parent::__construct($message, $previous);
    }
}