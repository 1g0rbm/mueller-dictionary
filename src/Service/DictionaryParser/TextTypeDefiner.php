<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;
use App\Service\DictionaryParser\TypeParser\SimpleParser;

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
            return SimpleParser::class;
        }

        throw UndefinedTextDictionaryTypeException::inText($text);
    }

    private function isArabian(string $text): bool
    {
        return $this->isMatch('/^\[.+] 1. _\w+\. /', $text);
    }

    private function isSimple(string $text): bool
    {
        return $this->isMatch('/^\[.+] _\w+\. /', $text);
    }

    private function isRomanian(string $text): bool
    {
        return $this->isMatch('/^I \[.+] _\w+\. /', $text);
    }

    private function isMatch(string $regex, string $text): bool
    {
        preg_match($regex, $text, $matches);

        return !(\count($matches) === 0);
    }
}
