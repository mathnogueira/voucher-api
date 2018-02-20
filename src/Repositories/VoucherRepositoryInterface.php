<?php

namespace App\Repositories;

use App\Models\Voucher;

interface VoucherRepositoryInterface
{
    public function getByCode(string $code);
    public function getByCodeAndEmail(string $code, string $email);
    public function getActiveVouchersByEmail(string $email);
    public function save(Voucher $voucher);
    public function update(Voucher $voucher);
}
