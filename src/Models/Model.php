<?php

namespace App\Models;

use App\Models\Validators;

abstract class Model
{
    protected $fields = array();
    protected $readonlyFields = array();
    protected $validations = array();
    protected $data = array();
    
    private $sealed = false;
    private $fieldValidator;

    protected function seal()
    {
        $this->sealed = true;
    }

    public function toAssocArray()
    {
        $assocArray = [];
        foreach ($this->fields as $field) {
            $value = isset($this->data[$field]) ? $this->data[$field] : null;
            $assocArray[$field] = $value;
        }

        return $assocArray;
    }

    public function __get($name)
    {
        if (in_array($name, $this->fields)) {
            return $this->data[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {

        if (!in_array($name, $this->fields) || ($this->sealed && in_array($name, $this->readonlyFields))) {
            throw new \Exception("The field \"$name\" is not writable because it is readonly or does not exist");
        }

        $this->data[$name] = $value;
    }

    public function getValidations()
    {
        return $this->validations;
    }
}
