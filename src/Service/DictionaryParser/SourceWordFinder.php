<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function mb_substr;
use function trim;

final class SourceWordFinder
{
    public function find(string $string): ?string
    {
        $endOfSourceWordPosition = self::getSourceWordEndPosition($string);
        if ($endOfSourceWordPosition === null) {
            return null;
        }

        return trim(mb_substr($string, 0, $endOfSourceWordPosition));
    }

    private static function getSourceWordEndPosition(string $string): ?int
    {
        $posI       = strpos($string, ' I ');
        $posBracket = strpos($string, ' [');

        if ($posI === false && $posBracket === false) {
            return null;
        }

        if ($posBracket === false) {
            return $posI;
        }

        if ($posI === false) {
            return $posBracket;
        }

        return $posI < $posBracket ? $posI : $posBracket;
    }
}
