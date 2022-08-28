<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TypeParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\PositionFinder;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use App\Service\DictionaryParser\TypeParser\ArabicParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 * @internal
 */
final class ArabicParserUnitTest extends KernelTestCase
{
    private ArabicParser $service;

    protected function setUp(): void
    {
        $sourceFinder        = new SourceWordFinder();
        $positionFinder      = new PositionFinder();
        $transcriptionFinder = new TranscriptionFinder();
        $translationParser   = new TranslationParser();

        $this->service = new ArabicParser($sourceFinder, $transcriptionFinder, $positionFinder, $translationParser);
    }

    /**
     * @throws ParsingPartNotFoundException
     */
    public function testParseReturnValidStructure(): void
    {
        $sourceString = "periodical [,pIэrI'OdIkэl] 1. _a. периодический; появляющийся через определённые промежутки времени; выпускаемый через определённые промежутки времени 2. _n. периодическое издание, журнал";

        $expectedWords = [
            new DictionaryWord(
                'periodical',
                "[,pIэrI'OdIkэl]",
                '_a.',
                [
                    'периодический',
                    'появляющийся через определённые промежутки времени',
                    'выпускаемый через определённые промежутки времени',
                ]
            ),
            new DictionaryWord(
                'periodical',
                "[,pIэrI'OdIkэl]",
                '_n.',
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
}
