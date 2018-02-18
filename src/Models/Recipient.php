<?php

namespace App\Models;

class Recipient extends Model
{
    protected $fields = ['id', 'name', 'email'];
    protected $readonlyFields = ['name', 'email'];
    protected $validations = [
        'name' => [
            ['not empty' => 'The recipient name cannot be empty']
        ],
        'email' => [
            ['valid email' => 'Recipient\'s email is invalid']
        ]
    ];
    
    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->seal();
    }
}