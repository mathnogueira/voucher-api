<?php

namespace App\Utils;

class UniqueStringGenerator
{
    private $alphabet;

    public function __construct(string $alphabet = "")
    {
        if (empty($alphabet)) {
            $this->alphabet = $this->getDefaultAlphabet();
        } else {
            $this->alphabet = $alphabet;
        }
    }

    private function getDefaultAlphabet()
    {
        return implode(range('a', 'z'))
            . implode(range('A', 'Z'))
            . implode(range(0, 9));
    }

    public function generate(int $length)
    {
        $alphabetLength = strlen($this->alphabet);
        $uniqueString = "";

        for ($i = 0; $i < $length; ++$i) {
            $randomInteger = $this->generateRandomInteger(0, $alphabetLength);
            $uniqueString .= $this->alphabet[$randomInteger];
        }

        return $uniqueString;
    }

    private function generateRandomInteger(int $min, int $max)
    {
        $range = $max - $min;
        $log2OfRange = log($range, 2);
        $bytes = (int) $log2OfRange/8 + 1;
        $bits = (int) $log2OfRange + 1;
        $filter = (int) (1 << $bits) - 1;

        do {
            $randomInteger = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $filteredInteger = $randomInteger & $filter;
        } while ($filteredInteger >= $range);

        return $min + $filteredInteger;
    }
}
