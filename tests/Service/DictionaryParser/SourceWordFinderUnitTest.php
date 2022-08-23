<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\SourceWordFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class SourceWordFinderUnitTest extends KernelTestCase
{
    private SourceWordFinder $service;

    protected function setUp(): void
    {
        $this->service = new SourceWordFinder();
    }

    public function testFindWordWhenNextPartIsTranscription(): void
    {
        $testString = 'been [bi:n] (полная форма); [bIn] (редуцированная форма) _p-p. от be';
        self::assertEquals(
            'been',
            $this->service->find($testString)
        );
    }

    public function testFindSourceWordWhenNextPartStartsWithRomanNumber(): void
    {
        $testString = "bat I [bЭt] _n. летучая мышь *) to have bats in one's belfry _разг. быть ненормальным; to go bats сходить с ума; like a bat out of hell очень быстро, со всех ног; blind as a bat совершенно слепой II [bЭt] 1. _n. 1) дубина; било (для льна); бита (в крикете); лапта; _редк. ракетка (для тенниса) 2) = batsman; a good bat хороший крикетист";
        self::assertEquals(
            'bat',
            $this->service->find($testString)
        );
    }
}
