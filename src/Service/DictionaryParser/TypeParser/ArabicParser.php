<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextReducerTrait;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;

final class ArabicParser implements TextTypeParserInterface
{
    use TextReducerTrait;

    private SourceWordFinder $sourceWordFinder;

    private TranscriptionFinder $transcriptionFinder;

    private PositionFinder $positionFinder;

    private TranslationParser $translationParser;

    public function __construct(
        SourceWordFinder $sourceWordFinder,
        TranscriptionFinder $transcriptionFinder,
        PositionFinder $positionFinder,
        TranslationParser $translationParser
    ) {
        $this->sourceWordFinder    = $sourceWordFinder;
        $this->transcriptionFinder = $transcriptionFinder;
        $this->positionFinder      = $positionFinder;
        $this->translationParser   = $translationParser;
    }

    /**
     * @throws ParsingPartNotFoundException
     */
    public function parse(string $text): array
    {
        $sourceWord = $this->sourceWordFinder->find($text);
        if ($sourceWord === null) {
            throw ParsingPartNotFoundException::sourceWord($text);
        }

        $text = $this->textReduce($sourceWord, $text);

        $transcription = $this->transcriptionFinder->find($text);
        if ($transcription === null) {
            throw ParsingPartNotFoundException::transcription($text);
        }

        $text = $this->textReduce($transcription, $text);

        $parts = array_map(
            static fn (string $part): string => trim($part),
            array_filter(preg_split('/\d\./', $text))
        );

        $result = [];
        foreach ($parts as $part) {
            $position = $this->positionFinder->find($part);
            if ($position === null) {
                throw ParsingPartNotFoundException::position($part);
            }

            $translations = $this->translationParser->parse($this->textReduce($position, $part));

            $result[] = new DictionaryWord($sourceWord, $transcription, $position, $translations);
        }

        return $result;
    }
}
