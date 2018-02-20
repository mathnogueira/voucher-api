<?php

namespace App\Generators;

use App\Utils\UniqueStringGenerator;

class VoucherCodeGenerator implements VoucherCodeGeneratorInterface
{
    private $uniqueStringGenerator;
    private $voucherCodeLength = 10;

    public function __construct()
    {
        $this->uniqueStringGenerator = new UniqueStringGenerator();
    }

    public function generate()
    {
        return $this->uniqueStringGenerator->generate($this->voucherCodeLength);
    }
}
