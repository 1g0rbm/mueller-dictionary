<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\TextTypeParserDuplicateException;
use App\Exception\DictionaryParser\TextTypeParserNotFoundException;
use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;
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
        $simpleParser        = new SimpleParser($sourceWordFinder, $transcriptionFinder, $positionFinder);

        $textTypeRegistry->addParser($simpleParser);

        $textTypeDefiner = new TextTypeDefiner();

        $this->service = new Parser($textTypeRegistry, $textTypeDefiner);
    }

    /**
     * @throws TextTypeParserNotFoundException
     * @throws UndefinedTextDictionaryTypeException
     */
    public function testParseSimpleTextReturnValidStructure(): void
    {
        $text = "beehive ['bi:haIv] _n. улей";

        $expected = [new DictionaryWord('beehive', "['bi:haIv]", '_n.', ['улей'])];

        $res = $this->service->parse($text);

        self::assertEquals($expected, $res);
    }
}
