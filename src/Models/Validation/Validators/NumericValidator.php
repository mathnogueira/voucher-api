<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\IValidator;

class NumericValidator implements IValidator
{
    public function validate($field)
    {
        return (bool) preg_match("/\d+(\.\d+)?/", $field);
    }
}