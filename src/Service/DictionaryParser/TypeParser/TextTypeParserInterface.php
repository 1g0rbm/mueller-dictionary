<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryElement;

interface TextTypeParserInterface
{
    /**
     * @return DictionaryElement[]
     */
    public function parse(string $text): array;
}
