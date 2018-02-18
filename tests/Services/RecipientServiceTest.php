<?php

namespace Tests\Services;

use App\Models\Recipient;
use PHPUnit\Framework\TestCase;
use App\Services\RecipientService;
use App\Models\Validation\ModelValidator;
use App\Repositories\IRecipientRepository;

class RecipientServiceTest extends TestCase
{
    private $recipientRepository;
    private $modelValidator;
    private $recipientService;

    protected function setUp()
    {
        $this->modelValidator = $this->createMock(ModelValidator::class);
        $this->recipientRepository = $this->createMock(IRecipientRepository::class);
        $this->recipientService = new RecipientService($this->recipientRepository, $this->modelValidator);
    }

    public function test_when_saving_an_invalid_recipient_should_throw_InvalidModelException()
    {
        $this->expectException(\App\Exceptions\InvalidModelException::class);
        $this->modelValidator->method('validate')->willReturn(['error']);

        $invalidModel = new Recipient("", "");
        
        $this->recipientService->save($invalidModel);
    }

    public function test_when_saving_a_recipient_using_an_already_existing_email_should_throw_ModelConflictException()
    {
        $this->expectException(\App\Exceptions\ModelConflictException::class);
        $this->modelValidator->method('validate')->willReturn([]);

        $duplicateEmailModel = new Recipient("John Doe", "already@existing.com");
        $this->recipientRepository->method('getByEmail')->willReturn(new Recipient("", ""));

        $this->recipientService->save($duplicateEmailModel);
    }

    public function test_when_saving_a_valid_recipient_should_save_it()
    {
        $this->modelValidator->method('validate')->willReturn([]);
        $this->recipientRepository->method('getByEmail')->willReturn(null);
        $validRecipient = new Recipient("John Doe", "john@doe.com");
        
        $this->recipientRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($validRecipient));

        $this->recipientService->save($validRecipient);
    }
}