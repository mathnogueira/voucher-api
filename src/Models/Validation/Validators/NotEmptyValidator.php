<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\IValidator;

class NotEmptyValidator implements IValidator
{
    public function validate($field)
    {
        return !empty($field);
    }
}