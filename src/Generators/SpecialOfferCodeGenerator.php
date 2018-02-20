<?php

namespace App\Generators;

use App\Utils\UniqueStringGenerator;

class SpecialOfferCodeGenerator implements SpecialOfferCodeGeneratorInterface
{
    private $uniqueStringGenerator;
    private $codeLength = 4;

    public function __construct()
    {
        $this->uniqueStringGenerator = new UniqueStringGenerator();
    }

    public function generate()
    {
        return $this->uniqueStringGenerator->generate($this->codeLength);
    }
}
