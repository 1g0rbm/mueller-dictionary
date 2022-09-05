<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function array_filter;
use function array_map;
use function preg_split;
use function trim;

final class PartOfSpeechSplitter
{
    /**
     * @return string[]
     */
    public function split(string $string): array
    {
        $parts =  array_map(
            static fn (string $part): string => trim($part),
            array_filter(preg_split('/\d\./', $string))
        );

        return $parts;
    }
}
