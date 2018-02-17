<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\IValidator;

class EmailValidator implements IValidator
{
    public function validate($field)
    {
        return filter_var($field, FILTER_VALIDATE_EMAIL);
    }
}