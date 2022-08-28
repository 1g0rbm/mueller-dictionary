<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function array_filter;
use function array_values;
use function preg_split;

final class RomanNumsTranslationSplitter
{
    /**
     * @return string[]
     */
    public function split(string $string): array
    {
        // :|
        $regex = '/^I | II | III | IV | V | VI | VII | VIII | IX | X /';

        $res = preg_split($regex, $string);
        if (!$res) {
            return [$string];
        }

        return array_values(array_filter($res));
    }
}
