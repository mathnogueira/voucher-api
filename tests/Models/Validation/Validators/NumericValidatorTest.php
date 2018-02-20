<?php

namespace Tests\Models\Validation\Validators;

use PHPUnit\Framework\TestCase;
use App\Models\Validation\Validators\NumericValidator;

class NumericValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        $this->validator = new NumericValidator();
    }

    function test_when_validating_an_integer_should_return_true()
    {
        $this->assertTrue($this->validator->validate(42));
    }

    function test_when_validating_a_very_long_number_should_return_true()
    {
        $this->assertTrue($this->validator->validate(4.2e42));
    }

    function test_when_validating_a_float_number_should_return_true()
    {
        $this->assertTrue($this->validator->validate(4.2));
    }

    function test_when_validating_a_non_numeric_string_should_return_false()
    {
        $this->assertFalse($this->validator->validate("abc"));
    }

    function test_when_validating_a_numeric_string_should_return_true()
    {
        $this->assertTrue($this->validator->validate("42.4224"));
    }
}
