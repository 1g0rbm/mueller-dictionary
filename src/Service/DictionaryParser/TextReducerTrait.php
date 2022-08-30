<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function trim;

trait TextReducerTrait
{
    private function textReduce(string $reducer, string $text): string
    {
        return trim(
            preg_replace(sprintf('/%s/', preg_quote($reducer, '/')), '', $text, 1)
        );
    }
}
