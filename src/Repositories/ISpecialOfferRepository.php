<?php

namespace App\Repositories;

interface ISpecialOfferRepository
{
    public function getByCode(string $code);
}