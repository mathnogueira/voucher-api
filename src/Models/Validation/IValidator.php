<?php

namespace App\Models\Validation;

interface IValidator
{
    public function validate($field);
}