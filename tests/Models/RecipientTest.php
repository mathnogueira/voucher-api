<?php

namespace Tests\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Recipient;

class RecipientTest extends TestCase
{

    public function test_when_name_is_empty_recipient_should_be_invalid()
    {
        $recipient = new Recipient("", "abc@com.uk");
        $this->assertFalse($recipient->isValid());
    }

    public function test_when_email_is_invalid_recipient_should_be_invalid()
    {
        $recipient = new Recipient("Matheus Nogueira", "matheus.nogueira2008");
        $this->assertFalse($recipient->isValid());
    }

    public function test_when_name_and_email_are_valid_recipient_should_be_valid()
    {
        $recipient = new Recipient("Matheus Nogueira", "matheus.nogueira2008@gmail.com");
        $this->assertTrue($recipient->isValid());
    }
}