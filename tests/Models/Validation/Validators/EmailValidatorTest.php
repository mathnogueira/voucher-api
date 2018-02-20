<?php

namespace Tests\Models\Validation\Validators;

use PHPUnit\Framework\TestCase;
use App\Models\Validation\Validators\EmailValidator;

class EmailValidatorTest extends TestCase
{
    private $validator;

    protected function setUp()
    {
        $this->validator = new EmailValidator();
    }

    public function test_when_validating_a_valid_email_should_return_true()
    {
        $this->assertTrue($this->validator->validate("matheus.nogueira2008@gmail.com"));
    }

    public function test_when_validating_an_invalid_email_should_return_false()
    {
        $this->assertFalse($this->validator->validate("doe@invalid"));
    }
}
