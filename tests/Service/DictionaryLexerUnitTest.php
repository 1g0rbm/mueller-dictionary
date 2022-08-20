<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\DictionaryLexer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
final class DictionaryLexerUnitTest extends KernelTestCase
{
    private DictionaryLexer $service;

    protected function setUp(): void
    {
        $this->service = new DictionaryLexer();
    }

    public function testDefineSourceWordWhenNextPartIsTranscription(): void
    {
        $testString = 'been [bi:n] (полная форма); [bIn] (редуцированная форма) _p-p. от be';
        self::assertEquals(
            'been',
            $this->service->defineSourceWord($testString)
        );
    }

    public function testDefineSourceWordWhenNextPartStartsWithRomanNumber(): void
    {
        $testString = "bat I [bЭt] _n. летучая мышь *) to have bats in one's belfry _разг. быть ненормальным; to go bats сходить с ума; like a bat out of hell очень быстро, со всех ног; blind as a bat совершенно слепой II [bЭt] 1. _n. 1) дубина; било (для льна); бита (в крикете); лапта; _редк. ракетка (для тенниса) 2) = batsman; a good bat хороший крикетист";
        self::assertEquals(
            'bat',
            $this->service->defineSourceWord($testString)
        );
    }

    public function testDefineTranscription(): void
    {
        $testString = 'been [bi:n] (полная форма); [bIn] (редуцированная форма) _p-p. от be';
        self::assertEquals(
            '[bi:n]',
            $this->service->defineTranscription($testString)
        );
    }
}
