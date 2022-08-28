<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\TextTypeParserNotFoundException;
use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;

final class Parser
{
    use TextReducerTrait;

    private TextTypeParserRegistry $textTypeParserRegistry;

    private TextTypeDefiner $textTypeDefiner;

    public function __construct(
        TextTypeParserRegistry $textTypeParserRegistry,
        TextTypeDefiner $textTypeDefiner
    ) {
        $this->textTypeParserRegistry = $textTypeParserRegistry;
        $this->textTypeDefiner        = $textTypeDefiner;
    }

    /**
     * @throws TextTypeParserNotFoundException
     * @throws UndefinedTextDictionaryTypeException
     * @return DictionaryWord[]
     */
    public function parse(string $text): array
    {
        return $this->textTypeParserRegistry
            ->getParser($this->textTypeDefiner->define($text))
            ->parse($text);
    }
}
