<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Service\DictionaryParser\DictionaryWordNormalizer;
use App\Service\DictionaryParser\PartOfSpeechNormalizer;
use App\Service\DictionaryParser\TranscriptionNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class DictionaryWordNormalizerUnitTest extends KernelTestCase
{
    private DictionaryWordNormalizer $service;

    protected function setUp(): void
    {
        $transcriptionNormalizer = new TranscriptionNormalizer();
        $posNormalizer           = new PartOfSpeechNormalizer();

        $this->service = new DictionaryWordNormalizer($transcriptionNormalizer, $posNormalizer);
    }

    /**
     * @dataProvider getTestsData
     */
    public function testNormalizeReturnValid(DictionaryWord $source, DictionaryWord $expected): void
    {
        self::assertEquals($expected, $this->service->normalize($source));
    }

    /**
     * @return DictionaryWord[][]
     */
    public function getTestsData(): array
    {
        return [
            [
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
                    'noun',
                    '[ˈæneks]',
                    [
                        'прибавление',
                        'приложение',
                        'дополнение',
                        'пристройка',
                        'крыло',
                        'флигель',
                    ]
                ),
            ],
        ];
    }
}
