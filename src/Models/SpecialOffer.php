<?php

namespace App\Models;

class SpecialOffer extends Model
{
    protected $fields = ['id', 'code', 'name', 'discount'];
    protected $readonlyFields = ['name', 'discount'];
    protected $validations = [
        'name' => [
            ['not empty' => 'The offer name cannot be empty']
        ],
        'discount' => [
            ['not empty' => 'The offer discount cannot be empty'],
            ['numeric' => 'The offer discount must be numeric']
        ]
    ];
    
    public function __construct(string $name, float $discount)
    {
        $this->name = $name;
        $this->discount = $discount;
    }
}
