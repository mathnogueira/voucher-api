<?php

namespace App\Services;

use App\Repositories\IRecipientRepository;
use App\Models\Recipient;

class RecipientService
{
    private $recipientRepository;

    public function __construct(IRecipientRepository $recipientRepository)
    {
        $this->recipientRepository = $recipientRepository;
    }

    public function save(Recipient $recipient)
    {
        if (!$recipient->isValid()) {
            
        }
    }
}