<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\ValidatorInterface;

class NotEmptyValidator implements ValidatorInterface
{
    public function validate($field)
    {
        return !empty($field);
    }
}