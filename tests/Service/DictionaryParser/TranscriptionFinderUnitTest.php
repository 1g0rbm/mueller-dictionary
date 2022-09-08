<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\TranscriptionFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @group unit
 */
final class TranscriptionFinderUnitTest extends KernelTestCase
{
    private TranscriptionFinder $service;

    protected function setUp(): void
    {
        $this->service = new TranscriptionFinder();
    }

    public function testFindTranscription(): void
    {
        $testString = 'been [bi:n] (полная форма); [bIn] (редуцированная форма) _p-p. от be';
        self::assertEquals(
            '[bi:n]',
            $this->service->find($testString)
        );
    }
}
