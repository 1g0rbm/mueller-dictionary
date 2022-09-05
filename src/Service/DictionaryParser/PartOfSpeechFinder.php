<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function preg_match;

final class PartOfSpeechFinder
{
    public function find(string $text): ?string
    {
        preg_match('/^_\w+\./', $text, $matches);

        if (\count($matches) === 0) {
            return null;
        }

        return $matches[0];
    }

    public function count(string $text): int
    {
        preg_match('/^_\w+\./', $text, $matches);

        return \count($matches);
    }
}
