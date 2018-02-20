<?php

namespace Tests\Models\Validation;

use PHPUnit\Framework\TestCase;
use App\Models\Validation\ValidatorFactory;

class ValidatorFactoryTest extends TestCase
{
    private $factory;

    protected function setUp()
    {
        $this->factory = new ValidatorFactory();
    }

    public function test_when_receiving_not_empty_should_return_NotEmptyValidator()
    {
        $validator = $this->factory->build('not empty');
        $this->assertInstanceOf(\App\Models\Validation\Validators\NotEmptyValidator::class, $validator);
    }

    public function test_when_receiving_valid_email_should_return_EmailValidator()
    {
        $validator = $this->factory->build('valid email');
        $this->assertInstanceOf(\App\Models\Validation\Validators\EmailValidator::class, $validator);
    }

    public function test_when_receiving_numeric_should_return_NumericValidator()
    {
        $validator = $this->factory->build('numeric');
        $this->assertInstanceOf(\App\Models\Validation\Validators\NumericValidator::class, $validator);
    }

    public function test_when_receiving_an_invalid_option_should_throw_Exception()
    {
        $this->expectException(\Exception::class);
        $validator = $this->factory->build('not valid option');
    }
}
