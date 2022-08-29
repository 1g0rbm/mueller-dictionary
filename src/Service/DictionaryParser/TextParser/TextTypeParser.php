<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TextParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\ArabicDotNumsSplitter;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\RomanNumsTranslationSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextReducerTrait;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;

final class TextTypeParser implements TextTypeParserInterface
{
    use TextReducerTrait;

    private SourceWordFinder $sourceWordFinder;

    private TranscriptionFinder $transcriptionFinder;

    private PositionFinder $positionFinder;

    private TranslationParser $translationParser;

    private RomanNumsTranslationSplitter $romanNumsSplitter;

    private ArabicDotNumsSplitter $arabicDotNumsSplitter;

    public function __construct(
        SourceWordFinder $sourceWordFinder,
        TranscriptionFinder $transcriptionFinder,
        PositionFinder $positionFinder,
        TranslationParser $translationParser,
        RomanNumsTranslationSplitter $romanNumsSplitter,
        ArabicDotNumsSplitter $arabicDotNumsSplitter
    ) {
        $this->sourceWordFinder      = $sourceWordFinder;
        $this->transcriptionFinder   = $transcriptionFinder;
        $this->positionFinder        = $positionFinder;
        $this->translationParser     = $translationParser;
        $this->romanNumsSplitter     = $romanNumsSplitter;
        $this->arabicDotNumsSplitter = $arabicDotNumsSplitter;
    }

    /**
     * {@inheritdoc}
     * @throws ParsingPartNotFoundException
     */
    public function parse(string $text): array
    {
        $result     = [];
        $sourceWord = $this->sourceWordFinder->find($text);
        if ($sourceWord === null) {
            throw ParsingPartNotFoundException::sourceWord($text);
        }

        $text = $this->textReduce($sourceWord, $text);

        $romanianParts = $this->romanNumsSplitter->split($text);
        foreach ($romanianParts as $romanianPart) {
            $transcription = $this->transcriptionFinder->find($romanianPart);
            if ($transcription === null) {
                throw ParsingPartNotFoundException::transcription($romanianPart);
            }

            $romanianPart = $this->textReduce($transcription, $romanianPart);

            $arabicDotParts = $this->arabicDotNumsSplitter->split($romanianPart);
            foreach ($arabicDotParts as $arabicDotPart) {
                $position = $this->positionFinder->find($arabicDotPart);
                if ($position === null) {
                    throw ParsingPartNotFoundException::position($arabicDotPart);
                }

                $translations = $this->translationParser->parse($this->textReduce($position, $arabicDotPart));

                $result[] = new DictionaryWord($sourceWord, $transcription, $position, $translations);
            }
        }

        return $result;
    }
}
