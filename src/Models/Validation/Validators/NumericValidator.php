<?php

namespace App\Models\Validation\Validators;

use App\Models\Validation\ValidatorInterface;

class NumericValidator implements ValidatorInterface
{
    public function validate($field)
    {
        return (bool) preg_match("/\d+(\.\d+)?/", $field);
    }
}
