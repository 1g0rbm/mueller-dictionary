<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Exception\DictionaryParser\TextTypeParserDuplicateException;
use App\Exception\DictionaryParser\TextTypeParserNotFoundException;
use App\Service\DictionaryParser\TypeParser\TextTypeParserInterface;

final class TextTypeParserRegistry
{
    /**
     * @var TextTypeParserInterface[]
     */
    private array $parsers = [];

    /**
     * @throws TextTypeParserNotFoundException
     */
    public function getParser(string $type): TextTypeParserInterface
    {
        if (!isset($this->parsers[$type])) {
            throw TextTypeParserNotFoundException::byType($type);
        }

        return $this->parsers[$type];
    }

    /**
     * @throws TextTypeParserDuplicateException
     */
    public function addParser(TextTypeParserInterface $parser): void
    {
        $type = $parser::class;

        if (isset($this->parsers[$type])) {
            throw TextTypeParserDuplicateException::byType($type);
        }

        $this->parsers[$type] = $parser;
    }
}
