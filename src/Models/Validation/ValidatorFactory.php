<?php

namespace App\Models\Validation;

use App\Models\Validation\Validators\NotEmptyValidator;
use App\Models\Validation\Validators\EmailValidator;
use App\Models\Validation\Validators\NumericValidator;

class ValidatorFactory
{
    public function build(string $rule)
    {
        switch ($rule) {
            case 'not empty':
                return new NotEmptyValidator();
            case 'valid email':
                return new EmailValidator();
            case 'numeric':
                return new NumericValidator();
            default:
                throw new \Exception("Validation rule \"$rule\" is not available.");
        }
    }
}
