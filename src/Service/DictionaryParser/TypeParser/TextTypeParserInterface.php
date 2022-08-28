<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;

interface TextTypeParserInterface
{
    /**
     * @return DictionaryWord[]
     */
    public function parse(string $text): array;
}
