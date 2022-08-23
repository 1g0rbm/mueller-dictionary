<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TypeParser\SimpleParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @internal
 */
final class SimpleParserUnitTest extends KernelTestCase
{
    private SimpleParser $service;

    protected function setUp(): void
    {
        $transcriptionFinder = new TranscriptionFinder();
        $positionFinder      = new PositionFinder();

        $this->service = new SimpleParser($transcriptionFinder, $positionFinder);
    }

    /**
     * @throws ParsingPartNotFoundException
     * @throws DictionaryElementInvalidTypeException
     */
    public function testParseReturnThreeElems(): void
    {
        $sourceText = "['bi:haIv] _n. улей";

        $expectedResult = [
            new DictionaryElement(DictionaryElement::TRANSCRIPTION_TYPE, "['bi:haIv]"),
            new DictionaryElement(DictionaryElement::POSITION_TYPE, '_n.'),
            new DictionaryElement(DictionaryElement::TRANSLATION_TYPE, ['улей']),
        ];

        self::assertEquals($expectedResult, $this->service->parse($sourceText));
    }
}
