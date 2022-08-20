<?php

declare(strict_types=1);

namespace App\Service;

use function mb_substr;
use function preg_match;
use function trim;

final class DictionaryLexer
{
    public function defineSourceWord(string $string): ?string
    {
        for ($i = 0; $i < \strlen($string); ++$i) {
            if (self::isEndOfSourceWord($string[$i])) {
                return trim(mb_substr($string, 0, $i));
            }
        }

        return null;
    }

    public function defineTranscription(string $string): ?string
    {
        $matches = [];
        preg_match('/\[(.+?)]/', $string, $matches);

        if (empty($matches)) {
            return null;
        }

        return $matches[0];
    }

    private static function isEndOfSourceWord(string $char): bool
    {
        $possibleStartChars = ['[', 'I'];

        return \in_array($char, $possibleStartChars, true);
    }
}
