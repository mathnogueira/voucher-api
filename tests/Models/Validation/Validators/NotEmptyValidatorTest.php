<?php

namespace Tests\Models\Validation\Validators;

use PHPUnit\Framework\TestCase;
use App\Models\Validation\Validators\NotEmptyValidator;

class NotEmptyValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        $this->validator = new NotEmptyValidator();
    }

    public function test_when_validating_an_empty_string_should_return_false()
    {
        $this->assertFalse($this->validator->validate(""));
    }

    public function test_when_validating_a_string_not_empty_should_return_true()
    {
        $this->assertTrue($this->validator->validate("hey there!"));
    }
}