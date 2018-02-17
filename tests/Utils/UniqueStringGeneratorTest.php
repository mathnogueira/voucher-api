<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\UniqueStringGenerator;

class UniqueStringGeneratorTest extends TestCase
{

    public function test_should_generate_a_string_of_requested_length()
    {
        $generator = new UniqueStringGenerator();
        $generatedString = $generator->generate(8);

        $this->assertEquals(8, strlen($generatedString));
    }

    public function test_should_generate_a_string_containing_only_the_allowed_chars()
    {
        $allowedChars = ['a', 'b', 'c', 0, 3];
        $generator = new UniqueStringGenerator(implode($allowedChars));
        $generatedString = $generator->generate(32);

        $this->assertTrue($this->stringContainsOnly($allowedChars, $generatedString));
    }

    private function stringContainsOnly(array $allowedChars, string $string)
    {
        $charArray = str_split($string);
        foreach ($charArray as $char) {
            if (!in_array($char, $allowedChars)) {
                return false;
            }
        }

        return true;
    }

    public function test_should_generate_unique_strings()
    {
        $generator = new UniqueStringGenerator();
        $generatedStrings = [];

        for ($i = 0; $i < 10000; ++$i) {
            $generatedStrings[$i] = $generator->generate(10);
        }

        rsort($generatedStrings);

        for ($i = 0; $i < 9999; ++$i) {
            $this->assertNotEquals($generatedStrings[$i], $generatedStrings[$i+1]);
        }
    }
}
