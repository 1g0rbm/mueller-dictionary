<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TranslationParser;

interface TranslationParserInterface
{
    /**
     * @return string[]
     */
    public function parse(string $translation): array;
}
