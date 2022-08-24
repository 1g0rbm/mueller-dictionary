<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function str_replace;
use function trim;

trait TextReducerTrait
{
    private function textReduce(string $text, string $reducer): string
    {
        return trim(str_replace($text, '', $reducer));
    }
}
