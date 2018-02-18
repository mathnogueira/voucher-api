<?php

namespace App\Repositories;

use App\Models\Voucher;

interface IVoucherRepository
{
    public function getByCode(string $code);
    public function save(Voucher $voucher);
}