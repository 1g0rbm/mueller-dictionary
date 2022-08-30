<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\TextParser\TextTypeParser;
use Psr\Log\LoggerInterface;

use function array_merge;
use function file_exists;
use function fopen;

final class DictionaryParser
{
    private WordsReader $wordsReader;

    private TextTypeParser $textTypeParser;

    private LoggerInterface $logger;

    public function __construct(WordsReader $wordsReader, TextTypeParser $textTypeParser, LoggerInterface $logger)
    {
        $this->wordsReader    = $wordsReader;
        $this->textTypeParser = $textTypeParser;
        $this->logger         = $logger;
    }

    /**
     * @throws DictionaryFileNotFoundException
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
            try {
                $parsed = array_merge($parsed, $this->textTypeParser->parse($rawWord));
            } catch (ParsingPartNotFoundException $e) {
                $this->logger->alert("Word did not parsed\n{$e}", $e->getTrace());
            }
        }

        return $parsed;
    }
}
