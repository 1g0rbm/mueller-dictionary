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
 * @internal
 * @group unit
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

    public function testMeaningOneTranscriptionReturnValid(): void
    {
        $source = "competence ['kOmpItэns] _n. 1) способность; умение; I doubt his competence for such work (или to do such work) я сомневаюсь, что у него есть данные для этой работы 2) компетентность 3) достаток, хорошее материальное положение 4) _юр. компетенция, правомочность";
        $expected = [
            new DictionaryWord(
                'competence',
                '_n.',
                "['kOmpItэns]",
                [
                    'способность',
                    'умение',
                    'I doubt his competence for such work (или to do such work) я сомневаюсь, что у него есть данные для этой работы',
                    'компетентность',
                    'достаток, хорошее материальное положение',
                    '_юр. компетенция, правомочность',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($source));
    }

    public function testMeaningTranscriptionReturnValid(): void
    {
        $source = "flagging I ['flЭgIN] 1. _pres-p. от flag III, 2 2. _n. устланная плитами мостовая; пол из плитняка II ['flЭgIN] 1. _pres-p. от flag IV 2. _a. слабеющий, никнущий III ['flЭgIN] _pres-p. от flag I, 2";
        $expected = [
            new DictionaryWord(
                'flagging',
                '_n.',
                "['flЭgIN]",
                [
                    'устланная плитами мостовая',
                    'пол из плитняка',
                ]
            ),
            new DictionaryWord(
                'flagging',
                '_a.',
                "['flЭgIN]",
                [
                    'слабеющий, никнущий',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($source));
    }

    public function testMeaningsSkipInvalidValuesReturnValid(): void
    {
        $source = "goggled ['gOgld] 1. _p-p. от goggle 3 2. _a. носящий защитные очки, в защитных очках";

        $expected = [
            new DictionaryWord(
                'goggled',
                '_a.',
                "['gOgld]",
                [
                    'носящий защитные очки, в защитных очках',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($source));
    }

    public function testMeaningsThatStartsWithIReturnValid(): void
    {
        $source = "Internet ['Intэ:net] _n. _комп. Интернет, международная компьютерная сеть";

        $expected = [
            new DictionaryWord(
                'Internet',
                '_n.',
                "['Intэ:net]",
                [
                    '_комп. Интернет, международная компьютерная сеть',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($source));
    }

    public function testMeaningsWithSeveralMeaningsAndSynonimsReturnValid(): void
    {
        $sourceString = "several ['sevrэl] _a. 1. 1) несколько; several people несколько человек 2) отдельный, особый, свой; they went their several ways каждый из них пошёл своей дорогой; each has his several ideal у каждого свой идеал; collective and several responsibility солидарная и личная ответственность; the several members of the Board отдельные члены правления 2. как сущ. несколько, некоторое количество; several of you некоторые из вас";

        $expected = [
            new DictionaryWord(
                'several',
                '_a.',
                "['sevrэl]",
                [
                    'несколько',
                    'several people несколько человек',
                    'отдельный, особый, свой',
                    'they went their several ways каждый из них пошёл своей дорогой',
                    'each has his several ideal у каждого свой идеал',
                    'collective and several responsibility солидарная и личная ответственность',
                    'the several members of the Board отдельные члены правления',
                ]
            ),
            new DictionaryWord(
                'several',
                '_a.',
                "['sevrэl]",
                [
                    'как сущ. несколько, некоторое количество',
                    'several of you некоторые из вас',
                ]
            ),
        ];

        self::assertEquals($expected, $this->service->parse($sourceString));
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
                    'прибавление, приложение, дополнение',
                    'пристройка, крыло, флигель',
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
                    'периодическое издание, журнал',
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
