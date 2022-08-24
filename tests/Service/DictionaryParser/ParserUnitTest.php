<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Exception\DictionaryParser\TextTypeParserDuplicateException;
use App\Exception\DictionaryParser\TextTypeParserNotFoundException;
use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;
use App\Service\DictionaryParser\Parser;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextTypeDefiner;
use App\Service\DictionaryParser\TextTypeParserRegistry;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TypeParser\SimpleParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class ParserUnitTest extends KernelTestCase
{
    private Parser $service;

    /**
     * @throws TextTypeParserDuplicateException
     */
    protected function setUp(): void
    {
        $sourceWordFinder = new SourceWordFinder();
        $textTypeRegistry = new TextTypeParserRegistry();

        $positionFinder      = new PositionFinder();
        $transcriptionFinder = new TranscriptionFinder();
        $simpleParser        = new SimpleParser($transcriptionFinder, $positionFinder);

        $textTypeRegistry->addParser($simpleParser);

        $textTypeDefiner = new TextTypeDefiner();

        $this->service = new Parser($sourceWordFinder, $textTypeRegistry, $textTypeDefiner);
    }

    /**
     * @throws ParsingPartNotFoundException
     * @throws DictionaryElementInvalidTypeException
     * @throws TextTypeParserNotFoundException
     * @throws UndefinedTextDictionaryTypeException
     */
    public function testParseSimpleTextReturnValidStructure(): void
    {
        $text = "beehive ['bi:haIv] _n. улей";

        $expected = [
            new DictionaryElement(DictionaryElement::SOURCE_TYPE, 'beehive'),
            new DictionaryElement(DictionaryElement::TRANSCRIPTION_TYPE, "['bi:haIv]"),
            new DictionaryElement(DictionaryElement::POSITION_TYPE, '_n.'),
            new DictionaryElement(DictionaryElement::TRANSLATION_TYPE, ['улей']),
        ];

        $res = $this->service->parse($text);

        self::assertEquals($expected, $res);
    }
}
