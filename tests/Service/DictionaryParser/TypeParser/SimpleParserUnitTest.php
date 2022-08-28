<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\SourceWordFinder;
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
        $sourceWord          = new SourceWordFinder();
        $transcriptionFinder = new TranscriptionFinder();
        $positionFinder      = new PositionFinder();

        $this->service = new SimpleParser($sourceWord, $transcriptionFinder, $positionFinder);
    }

    /**
     * @throws ParsingPartNotFoundException
     */
    public function testParseReturnThreeElems(): void
    {
        $sourceText = "beehive ['bi:haIv] _n. улей";

        $expectedWord = [new DictionaryWord('beehive', "['bi:haIv]", '_n.', ['улей'])];

        self::assertEquals($expectedWord, $this->service->parse($sourceText));
    }
}
