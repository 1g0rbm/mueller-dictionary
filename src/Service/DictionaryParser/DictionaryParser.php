<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\TextParser\TextTypeParser;

final class DictionaryParser
{
    private WordsReader $wordsReader;

    private TextTypeParser $textTypeParser;

    public function __construct(WordsReader $wordsReader, TextTypeParser $textTypeParser)
    {
        $this->wordsReader    = $wordsReader;
        $this->textTypeParser = $textTypeParser;
    }

    /**
     * @throws DictionaryFileNotFoundException
     * @throws ParsingPartNotFoundException
     * @return DictionaryWord[]
     */
    public function parse(string $filePath, int $batchAmount): array
    {
        if (!file_exists($filePath)) {
            throw DictionaryFileNotFoundException::byPath($filePath);
        }

        $fp = fopen($filePath, 'rb');

        $parsed   = [];
        $rawWords = $this->wordsReader->read($fp, $batchAmount);
        foreach ($rawWords as $rawWord) {
            $parsed = array_merge($parsed, $this->textTypeParser->parse($rawWord));
        }

        return $parsed;
    }
}
