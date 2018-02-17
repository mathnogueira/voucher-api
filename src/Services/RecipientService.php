<?php

namespace App\Services;

use App\Repositories\IRecipientRepository;
use App\Models\Recipient;
use App\Models\Validation\ModelValidator;
use App\Exceptions\InvalidModelException;
use App\Exceptions\ModelConflictException;

class RecipientService
{
    private $recipientRepository;
    private $modelValidator;

    public function __construct(
        IRecipientRepository $recipientRepository,
        ModelValidator $modelValidator
    ) {
        $this->recipientRepository = $recipientRepository;
        $this->modelValidator = $modelValidator;
    }

    public function save(Recipient $recipient)
    {
        $modelErrors = $this->modelValidator->validate($recipient);
        if (count($modelErrors) > 0) {
            throw new InvalidModelException($modelErrors);
        }

        $existingRecipient = $this->recipientRepository->getByEmail($recipient->email);
        if ($existingRecipient != null) {
            throw new ModelConflictException("This email is already in use");
        }
        
        $this->recipientRepository->save($recipient);
    }
}