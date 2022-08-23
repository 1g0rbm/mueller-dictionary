<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;

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

        if ($this->isArabian($text)) {
            return 'arabian';
        }

        if ($this->isSimple($text)) {
            return 'simple';
        }

        throw UndefinedTextDictionaryTypeException::inText($text);
    }

    private function isArabian(string $text): bool
    {
        $regex = '/^\[.+] 1. _\w+\. /';

        preg_match($regex, $text, $matches);

        if (\count($matches) === 0) {
            return false;
        }

        return true;
    }

    private function isSimple(string $text): bool
    {
        $regex = '/^\[.+] _\w+\. /';

        preg_match($regex, $text, $matches);

        if (\count($matches) === 0) {
            return false;
        }

        return true;
    }

    private function isRomanian(string $text): bool
    {
        $regex = '/^I \[.+] _\w+\. /';

        preg_match($regex, $text, $matches);

        if (\count($matches) === 0) {
            return false;
        }

        return true;
    }
}
