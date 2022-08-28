<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextReducerTrait;
use App\Service\DictionaryParser\TranscriptionFinder;

use function array_map;
use function explode;
use function trim;

final class SimpleParser implements TextTypeParserInterface
{
    use TextReducerTrait;

    private SourceWordFinder $sourceWordFinder;

    private TranscriptionFinder $transcriptionFinder;

    private PositionFinder $positionFinder;

    public function __construct(
        SourceWordFinder $sourceWordFinder,
        TranscriptionFinder $transcriptionFinder,
        PositionFinder $positionFinder
    ) {
        $this->sourceWordFinder    = $sourceWordFinder;
        $this->transcriptionFinder = $transcriptionFinder;
        $this->positionFinder      = $positionFinder;
    }

    /**
     * @throws ParsingPartNotFoundException
     * @return DictionaryWord[]
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

        $position = $this->positionFinder->find($text);
        if ($position === null) {
            throw ParsingPartNotFoundException::position($text);
        }

        $translations = array_map(
            static fn (string $translation): string => trim($translation),
            explode(',', $this->textReduce($position, $text))
        );

        return [new DictionaryWord($sourceWord, $transcription, $position, $translations)];
    }
}
