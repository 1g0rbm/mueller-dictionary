<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\PartOfSpeechFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @internal
 */
final class PartOfSpeechFinderUnitTest extends KernelTestCase
{
    private PartOfSpeechFinder $service;

    protected function setUp(): void
    {
        $this->service = new PartOfSpeechFinder();
    }

    public function testFindReturnPosition(): void
    {
        $source = '_n. улей';

        self::assertEquals('_n.', $this->service->find($source));
    }

    public function testFindReturnNull(): void
    {
        $source = 'noun улей';

        self::assertNull($this->service->find($source));
    }
}
