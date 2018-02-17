<?php

namespace App\Exceptions;

class InvalidModelException extends \Exception
{
    private $errors;

    public function __construct(array $modelErrors)
    {
        parent::__construct();
        $this->errors = $modelErrors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}