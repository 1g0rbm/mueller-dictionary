<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function mb_substr;
use function trim;

final class SourceWordFinder
{
    public function find(string $string): ?string
    {
        for ($i = 0; $i < \strlen($string); ++$i) {
            if (self::isEndOfSourceWord($string[$i])) {
                return trim(mb_substr($string, 0, $i));
            }
        }

        return null;
    }

    private static function isEndOfSourceWord(string $char): bool
    {
        $possibleStartChars = ['[', 'I'];

        return \in_array($char, $possibleStartChars, true);
    }
}
