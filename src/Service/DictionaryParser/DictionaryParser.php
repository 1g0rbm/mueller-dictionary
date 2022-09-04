<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\Word;
use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Repository\WordRepository;
use App\Service\DictionaryParser\TextParser\TextTypeParser;
use App\Service\Flusher;
use Psr\Log\LoggerInterface;

use function file_exists;
use function fopen;

final class DictionaryParser
{
    private WordsReader $wordsReader;

    private TextTypeParser $textTypeParser;

    private DictionaryWordNormalizer $dictionaryWordNormalizer;

    private WordRepository $wordRepository;

    private Flusher $flusher;

    private LoggerInterface $logger;

    public function __construct(
        WordsReader $wordsReader,
        TextTypeParser $textTypeParser,
        DictionaryWordNormalizer $dictionaryWordNormalizer,
        Flusher $flusher,
        WordRepository $wordRepository,
        LoggerInterface $logger
    ) {
        $this->wordsReader              = $wordsReader;
        $this->textTypeParser           = $textTypeParser;
        $this->dictionaryWordNormalizer = $dictionaryWordNormalizer;
        $this->wordRepository           = $wordRepository;
        $this->flusher                  = $flusher;
        $this->logger                   = $logger;
    }

    /**
     * @throws DictionaryFileNotFoundException
     */
    public function parse(string $filePath, int $batchAmount): void
    {
        if (!file_exists($filePath)) {
            throw DictionaryFileNotFoundException::byPath($filePath);
        }

        $fp = fopen($filePath, 'rb');
        do {
            $rawWords = $this->wordsReader->read($fp, $batchAmount);
            foreach ($rawWords as $rawWord) {
                try {
                    $meanings = $this->textTypeParser->parse($rawWord);
                    foreach ($meanings as $meaning) {
                        $this->wordRepository->add(
                            Word::createFromDto($this->dictionaryWordNormalizer->normalize($meaning))
                        );
                    }
                } catch (ParsingPartNotFoundException $e) {
                    $this->logger->alert("Word did not parsed\n{$e}", $e->getTrace());
                }
            }
            $this->flusher->flush();
        } while (\count($rawWords) > 0);
    }
}
