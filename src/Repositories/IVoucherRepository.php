<?php

namespace App\Repositories;

interface IVoucherRepository
{
    public function saveAll(array $vouchers);
}