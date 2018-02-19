<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\ValidatorInterface;

class EmailValidator implements ValidatorInterface
{
    public function validate($field)
    {
        return (bool) filter_var($field, FILTER_VALIDATE_EMAIL);
    }
}