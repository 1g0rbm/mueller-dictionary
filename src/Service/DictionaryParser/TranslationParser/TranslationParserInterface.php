<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TranslationParser;

use App\Entity\DictionaryParser\DictionaryElement;

interface TranslationParserInterface
{
    /**
     * @return DictionaryElement[]
     */
    public function parse(string $translation): array;
}
