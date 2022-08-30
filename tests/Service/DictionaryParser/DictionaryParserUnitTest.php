<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Service\DictionaryParser\DictionaryParser;
use App\Service\DictionaryParser\MeaningSplitter;
use App\Service\DictionaryParser\PartOfSpeechFinder;
use App\Service\DictionaryParser\PartOfSpeechSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextParser\TextTypeParser;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use App\Service\DictionaryParser\WordsReader;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 * @psalm-suppress MixedMethodCall
 * @psalm-suppress PossiblyUndefinedMethod
 * @internal
 */
final class DictionaryParserUnitTest extends KernelTestCase
{
    private DictionaryParser $service;

    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private LoggerInterface|MockObject $loggerMock;

    protected function setUp(): void
    {
        $wordReader = new WordsReader();

        $sourceFinder          = new SourceWordFinder();
        $positionFinder        = new PartOfSpeechFinder();
        $transcriptionFinder   = new TranscriptionFinder();
        $translationParser     = new TranslationParser();
        $romanNumsSplitter     = new MeaningSplitter();
        $arabicDotNumsSplitter = new PartOfSpeechSplitter();

        $textParser = new TextTypeParser(
            $sourceFinder,
            $transcriptionFinder,
            $positionFinder,
            $translationParser,
            $romanNumsSplitter,
            $arabicDotNumsSplitter
        );

        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->service = new DictionaryParser($wordReader, $textParser, $this->loggerMock);
    }

    /**
     * @throws DictionaryFileNotFoundException
     */
    public function testParseReturnValid(): void
    {
        $expected = [
            new DictionaryWord(
                'beehive',
                "['bi:haIv]",
                '_n.',
                ['улей']
            ),
            new DictionaryWord(
                'periodicity',
                "[,pIэrIэ'dIsItI]",
                '_n.',
                ['периодичность', 'частота', '_физиол. менструации']
            ),
        ];

        $this->loggerMock
            ->expects(self::once())
            ->method('alert')
            ->with(
                'Word did not parsed
[dictionary parser] Part of speech not found in string "(полная форма); [bIn] (редуцированная форма) _p-p. от be"'
            );

        self::assertEquals($expected, $this->service->parse(__DIR__ . '/fixtures/words', 3));
    }
}
