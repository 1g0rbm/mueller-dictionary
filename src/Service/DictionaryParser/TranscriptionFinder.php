<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function preg_match;

final class TranscriptionFinder
{
    public function find(string $string): ?string
    {
        $matches = [];
        preg_match('/\[(.+?)]/', $string, $matches);

        if (empty($matches)) {
            return null;
        }

        return $matches[0];
    }
}
