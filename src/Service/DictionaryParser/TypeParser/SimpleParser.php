<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\TranscriptionFinder;

use function array_map;
use function explode;
use function trim;

final class SimpleParser implements TextTypeParserInterface
{
    private TranscriptionFinder $transcriptionFinder;

    private PositionFinder $positionFinder;

    public function __construct(
        TranscriptionFinder $transcriptionFinder,
        PositionFinder $positionFinder
    ) {
        $this->transcriptionFinder = $transcriptionFinder;
        $this->positionFinder      = $positionFinder;
    }

    /**
     * @throws DictionaryElementInvalidTypeException|ParsingPartNotFoundException
     * @return DictionaryElement[]
     */
    public function parse(string $text): array
    {
        $transcription = $this->transcriptionFinder->find($text);
        if ($transcription === null) {
            throw ParsingPartNotFoundException::transcription($text);
        }

        $text = $this->reduce($transcription, $text);

        $position = $this->positionFinder->find($text);
        if ($position === null) {
            throw ParsingPartNotFoundException::position($text);
        }

        $translations = array_map(
            static fn (string $translation): string => trim($translation),
            explode(',', $this->reduce($position, $text))
        );

        return [
            new DictionaryElement(DictionaryElement::TRANSCRIPTION_TYPE, $transcription),
            new DictionaryElement(DictionaryElement::POSITION_TYPE, $position),
            new DictionaryElement(DictionaryElement::TRANSLATION_TYPE, $translations),
        ];
    }

    private function reduce(string $part, string $string): string
    {
        return trim(str_replace($part, '', $string));
    }
}
