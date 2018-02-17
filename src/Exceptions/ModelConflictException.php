<?php

namespace App\Exceptions;

class ModelConflictException extends \Exception
{
    public function __construct(string $reason)
    {
        parent::__construct($reason);
    }
}