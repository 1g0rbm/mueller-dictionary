<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\TranscriptionNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class TranscriptionNormalizerUnitTest extends KernelTestCase
{
    private TranscriptionNormalizer $service;

    protected function setUp(): void
    {
        $this->service = new TranscriptionNormalizer();
    }

    /**
     * @dataProvider transcriptions
     */
    public function testNormalizeReturnValid(string $testing, string $expected): void
    {
        self::assertEquals($expected, $this->service->normalize($testing));
    }

    /**
     * @return string[][]
     */
    public function transcriptions(): array
    {
        return [
            ["['dзelI]", '[ˈʤelɪ]'],
            ['[mЭn]', '[mæn]'],
            ['[bэul]', '[bəul]'],
            ['[tSIэ]', '[ʧɪə]'],
            ['[lэu]', '[ləu]'],
        ];
    }
}
