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
        $pos = strpos($string, ' I ');
        if ($pos) {
            return $pos;
        }

        $pos = strpos($string, ' [');
        if ($pos) {
            return $pos;
        }

        return null;
    }
}
