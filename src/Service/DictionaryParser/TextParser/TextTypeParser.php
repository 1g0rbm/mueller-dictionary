<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TextParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\MeaningSplitter;
use App\Service\DictionaryParser\PartOfSpeechFinder;
use App\Service\DictionaryParser\PartOfSpeechSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextReducerTrait;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;

final class TextTypeParser implements TextTypeParserInterface
{
    use TextReducerTrait;

    private SourceWordFinder $sourceWordFinder;

    private TranscriptionFinder $transcriptionFinder;

    private PartOfSpeechFinder $partOfSpeechFinder;

    private TranslationParser $translationParser;

    private MeaningSplitter $meaningSplitter;

    private PartOfSpeechSplitter $partOfSpeechSplitter;

    public function __construct(
        SourceWordFinder $sourceWordFinder,
        TranscriptionFinder $transcriptionFinder,
        PartOfSpeechFinder $positionFinder,
        TranslationParser $translationParser,
        MeaningSplitter $meaningSplitter,
        PartOfSpeechSplitter $partOfSpeechSplitter
    ) {
        $this->sourceWordFinder     = $sourceWordFinder;
        $this->transcriptionFinder  = $transcriptionFinder;
        $this->partOfSpeechFinder   = $positionFinder;
        $this->translationParser    = $translationParser;
        $this->meaningSplitter      = $meaningSplitter;
        $this->partOfSpeechSplitter = $partOfSpeechSplitter;
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

        $meanings = $this->meaningSplitter->split($text);

        foreach ($meanings as $meaning) {
            $transcription = $this->transcriptionFinder->find($meaning);
            if ($transcription === null) {
                throw ParsingPartNotFoundException::transcription($meaning);
            }

            $meaning = $this->textReduce($transcription, $meaning);

            $pos = $this->partOfSpeechFinder->count($meaning) === 1 ? $this->partOfSpeechFinder->find($meaning) : null;
            if ($pos) {
                $meaning = $this->textReduce($pos, $meaning);
            }

            $partsOfSpeech = $this->partOfSpeechSplitter->split($meaning);

            foreach ($partsOfSpeech as $partOfSpeech) {
                $pos = $this->partOfSpeechFinder->find($partOfSpeech) ?? $pos;
                if ($pos === null) {
                    continue;
                }

                $translations = $this->translationParser->parse($this->textReduce($pos, $partOfSpeech));

                $result[] = new DictionaryWord($sourceWord, $pos, $transcription, $translations);
            }
        }

        return $result;
    }
}
