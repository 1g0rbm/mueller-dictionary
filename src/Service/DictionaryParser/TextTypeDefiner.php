<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;

use function count;
use function preg_match_all;

final class TextTypeDefiner
{
    /**
     * @throws UndefinedTextDictionaryTypeException
     */
    public function define(string $text): string
    {
        if ($this->isRomanian($text)) {
            return 'romanian';
        }

        throw UndefinedTextDictionaryTypeException::inText($text);
    }

    private function isRomanian(string $text): bool
    {
        $regex = '/I | II | III | IV | V | VI | VII | VIII | IX | X /';

        preg_match_all($regex, $text, $matches);

        $matches = $matches[0] ?? null;

        if ($matches === null) {
            return false;
        }

        if (count($matches) === 0) {
            return false;
        }

        return true;
    }
}
