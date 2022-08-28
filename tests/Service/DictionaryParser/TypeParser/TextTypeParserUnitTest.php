<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\ArabicDotNumsSplitter;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\RomanNumsTranslationSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use App\Service\DictionaryParser\TypeParser\RomanianParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @internal
 */
final class TextTypeParserUnitTest extends KernelTestCase
{
    private RomanianParser $service;

    protected function setUp(): void
    {
        $sourceFinder          = new SourceWordFinder();
        $positionFinder        = new PositionFinder();
        $transcriptionFinder   = new TranscriptionFinder();
        $translationParser     = new TranslationParser();
        $romanNumsSplitter     = new RomanNumsTranslationSplitter();
        $arabicDotNumsSplitter = new ArabicDotNumsSplitter();

        $this->service = new RomanianParser(
            $sourceFinder,
            $transcriptionFinder,
            $positionFinder,
            $translationParser,
            $romanNumsSplitter,
            $arabicDotNumsSplitter
        );
    }

    /**
     * @throws ParsingPartNotFoundException
     */
    public function testRomanianStringReturnValid(): void
    {
        $sourceString = "annex I ['Эneks] _n. 1) прибавление, приложение, дополнение 2) пристройка, крыло, флигель II [э'neks] _v. 1) присоединять; аннексировать 2) прилагать; делать приложение (к книге и т.п.)";

        $expected = [
            new DictionaryWord(
                'annex',
                "['Эneks]",
                '_n.',
                [
                    'прибавление',
                    'приложение',
                    'дополнение',
                    'пристройка',
                    'крыло',
                    'флигель',
                ]
            ),
            new DictionaryWord(
                'annex',
                "[э'neks]",
                '_v.',
                [
                    'присоединять',
                    'аннексировать',
                    'прилагать',
                    'делать приложение (к книге и т.п.)',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($sourceString));
    }
}
