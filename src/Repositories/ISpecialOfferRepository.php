<?php

namespace App\Repositories;

use App\Models\SpecialOffer;

interface ISpecialOfferRepository
{
    public function getAll();
    public function getById(int $id);
    public function getByCode(string $code);
    public function save(SpecialOffer $specialOffer);
}