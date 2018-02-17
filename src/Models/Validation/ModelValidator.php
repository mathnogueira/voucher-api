<?php

namespace App\Models\Validation;

use App\Model\Model;

class ModelValidator
{
    private $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function validate($model)
    {
        $errors = [];
        $validationRules = $model->validations;
        foreach ($validationRules as $field => $rules) {
            $fieldValue = $model->{$field};
            $fieldErrors = $this->runFieldValidations($fieldValue, $rules);
            $errors = array_merge($errors, $fieldErrors);
        }

        return $errors;
    }

    private function runFieldValidations($fieldValue, $rules)
    {
        $fieldErrors = [];
        foreach ($rules as $rule) {
            foreach ($rule as $validatorName => $failMessage) { 
                $validator = $this->validatorFactory->build($validatorName);
                if (!$validator->validate($fieldValue)) {
                    $fieldErrors[] = $failMessage;
                }
            }
        }

        return $fieldErrors;
    }
}