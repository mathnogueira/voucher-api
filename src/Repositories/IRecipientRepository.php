<?php

namespace App\Repositories;

use App\Models\Recipient;

interface IRecipientRepository
{
    public function getAll();
    public function getByEmail(string $email);
    public function save(Recipient $recipient);
}