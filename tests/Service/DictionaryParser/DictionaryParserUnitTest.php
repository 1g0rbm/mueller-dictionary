<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Exception\DictionaryParser\ParsingPartNotFoundException;
use App\Service\DictionaryParser\DictionaryParser;
use App\Service\DictionaryParser\MeaningSplitter;
use App\Service\DictionaryParser\PartOfSpeechFinder;
use App\Service\DictionaryParser\PartOfSpeechSplitter;
use App\Service\DictionaryParser\SourceWordFinder;
use App\Service\DictionaryParser\TextParser\TextTypeParser;
use App\Service\DictionaryParser\TranscriptionFinder;
use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use App\Service\DictionaryParser\WordsReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
final class DictionaryParserUnitTest extends KernelTestCase
{
    private DictionaryParser $service;

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

        $this->service = new DictionaryParser($wordReader, $textParser);
    }

    /**
     * @throws DictionaryFileNotFoundException
     * @throws ParsingPartNotFoundException
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

        self::assertEquals($expected, $this->service->parse(__DIR__ . '/fixtures/words', 2));
    }
}
