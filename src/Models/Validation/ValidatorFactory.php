<?php

namespace App\Models\Validation;

use App\Models\Validation\Validators;

class ValidatorFactory
{
    public function build(string $rule)
    {
        switch ($rule) {
            case 'not empty':
                return new \NotEmptyValidator();
            case 'valid email':
                return new \EmailValidator();
            default:
                throw new Exception("Validation rule \"$rule\" is not available.");
        }
    }
}