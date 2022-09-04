<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TextParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\MeaningSplitter;
use App\Service\DictionaryParser\PartOfSpeechFinder;
use App\Service\DictionaryParser\PartOfSpeechSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextParser\TextTypeParser;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @internal
 */
final class TextTypeParserUnitTest extends KernelTestCase
{
    private TextTypeParser $service;

    protected function setUp(): void
    {
        $sourceFinder          = new SourceWordFinder();
        $positionFinder        = new PartOfSpeechFinder();
        $transcriptionFinder   = new TranscriptionFinder();
        $translationParser     = new TranslationParser();
        $romanNumsSplitter     = new MeaningSplitter();
        $arabicDotNumsSplitter = new PartOfSpeechSplitter();

        $this->service = new TextTypeParser(
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
    public function testMeaningsReturnValid(): void
    {
        $sourceString = "annex I ['Эneks] _n. 1) прибавление, приложение, дополнение 2) пристройка, крыло, флигель II [э'neks] _v. 1) присоединять; аннексировать 2) прилагать; делать приложение (к книге и т.п.)";

        $expected = [
            new DictionaryWord(
                'annex',
                '_n.',
                "['Эneks]",
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
                '_v.',
                "[э'neks]",
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

    /**
     * @throws ParsingPartNotFoundException
     */
    public function testPartsOfSpeechReturnValid(): void
    {
        $sourceString = "periodical [,pIэrI'OdIkэl] 1. _a. периодический; появляющийся через определённые промежутки времени; выпускаемый через определённые промежутки времени 2. _n. периодическое издание, журнал";

        $expectedWords = [
            new DictionaryWord(
                'periodical',
                '_a.',
                "[,pIэrI'OdIkэl]",
                [
                    'периодический',
                    'появляющийся через определённые промежутки времени',
                    'выпускаемый через определённые промежутки времени',
                ]
            ),
            new DictionaryWord(
                'periodical',
                '_n.',
                "[,pIэrI'OdIkэl]",
                [
                    'периодическое издание',
                    'журнал',
                ]
            ),
        ];

        self::assertEquals(
            $expectedWords,
            $this->service->parse($sourceString)
        );
    }

    /**
     * @throws ParsingPartNotFoundException
     */
    public function testSimpleStringReturnValid(): void
    {
        $sourceText = "beehive ['bi:haIv] _n. улей";

        $expectedWord = [new DictionaryWord('beehive', '_n.', "['bi:haIv]", ['улей'])];

        self::assertEquals($expectedWord, $this->service->parse($sourceText));
    }
}
