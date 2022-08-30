<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\WordsReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use function fopen;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class WordsReaderUnitTest extends KernelTestCase
{
    private WordsReader $service;

    protected function setUp(): void
    {
        $this->service = new WordsReader();
    }

    public function testReadLinesByCount(): void
    {
        $fp    = fopen(__DIR__ . '/fixtures/words', 'rb');
        $words = $this->service->read($fp, 2);

        self::assertCount(2, $words);
        self::assertEquals("beehive ['bi:haIv] _n. улей", $words[0]);
    }

    public function testReadLinesToEnd(): void
    {
        $fp    = fopen(__DIR__ . '/fixtures/words', 'rb');
        $words = $this->service->read($fp);

        self::assertCount(7, $words);
        self::assertEquals("beehive ['bi:haIv] _n. улей", $words[0]);
    }

    public function testReadLinesPartials(): void
    {
        $fp    = fopen(__DIR__ . '/fixtures/words', 'rb');
        $words = $this->service->read($fp, 1);

        self::assertCount(1, $words);
        self::assertEquals("beehive ['bi:haIv] _n. улей", $words[0]);

        $words = $this->service->read($fp, 1);

        self::assertCount(1, $words);
        self::assertEquals(
            'been [bi:n] (полная форма); [bIn] (редуцированная форма) _p-p. от be',
            $words[0]
        );
    }
}
