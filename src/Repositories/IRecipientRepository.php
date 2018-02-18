<?php

namespace App\Repositories;

use App\Models\Recipient;
use App\Models\SpecialOffer;

interface IRecipientRepository
{
    public function getAll();
    public function getByEmail(string $email);
    public function getAllRecipientsDoesntHaveVoucherFor(SpecialOffer $specialOffer);

    public function save(Recipient $recipient);
}