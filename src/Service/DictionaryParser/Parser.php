<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Exception\DictionaryParser\TextTypeParserNotFoundException;
use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;

final class Parser
{
    use TextReducerTrait;

    private SourceWordFinder $sourceWordFinder;

    private TextTypeParserRegistry $textTypeParserRegistry;

    private TextTypeDefiner $textTypeDefiner;

    public function __construct(
        SourceWordFinder $sourceWordFinder,
        TextTypeParserRegistry $textTypeParserRegistry,
        TextTypeDefiner $textTypeDefiner
    ) {
        $this->sourceWordFinder       = $sourceWordFinder;
        $this->textTypeParserRegistry = $textTypeParserRegistry;
        $this->textTypeDefiner        = $textTypeDefiner;
    }

    /**
     * @throws ParsingPartNotFoundException
     * @throws DictionaryElementInvalidTypeException
     * @throws TextTypeParserNotFoundException
     * @throws UndefinedTextDictionaryTypeException
     */
    public function parse(string $text): array
    {
        $sourceWord = $this->sourceWordFinder->find($text);
        if ($sourceWord === null) {
            throw ParsingPartNotFoundException::sourceWord($text);
        }

        $text = $this->textReduce($sourceWord, $text);

        $elems = $this->textTypeParserRegistry
            ->getParser($this->textTypeDefiner->define($text))
            ->parse($text);

        return [
            new DictionaryElement(DictionaryElement::SOURCE_TYPE, $sourceWord),
            ...$elems,
        ];
    }
}
