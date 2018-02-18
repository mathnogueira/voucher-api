<?php

namespace Tests\Models\Validation;

use PHPUnit\Framework\TestCase;
use App\Models\Recipient;
use App\Models\Validation\ModelValidator;
use App\Models\Validation\ValidatorFactory;
use App\Models\Validation\IValidator;

class ModelValidatorTest extends TestCase
{
    public function test_should_return_messages_for_all_invalid_fields_of_a_model()
    {
        
        $validatorFactory = $this->createMock(ValidatorFactory::class);
        $validator = $this->createMock(IValidator::class);

        // Returns false for the empty name
        $validator
            ->expects($this->at(0))
            ->method('validate')
            ->with($this->equalTo(""))
            ->willReturn(false);

        // Returns true for the valid email
        $validator
            ->expects($this->at(1))
            ->method('validate')
            ->with($this->equalTo('asd@asd.com'))
            ->willReturn(true);

        $validatorFactory->method('build')->willReturn($validator);
        
        $model = new TestModel("", "asd@asd.com");
        $modelValidator = new ModelValidator($validatorFactory);

        $errors = $modelValidator->validate($model);

        $this->assertEquals(1, count($errors));
        $this->assertEquals('Name must contain a value', $errors[0]);
    }
}

class TestModel extends \App\Models\Recipient
{
    protected $validations = [
        'name' => [
            ['not empty' => 'Name must contain a value']
        ],
        'email' => [
            ['valid email' => 'Email must be valid']
        ]
    ];

    public function __construct($name, $email)
    {
        parent::__construct($name, $email);
    }
}
