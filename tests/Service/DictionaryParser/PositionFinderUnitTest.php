<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\PositionFinder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 *
 * @internal
 */
final class PositionFinderUnitTest extends KernelTestCase
{
    private PositionFinder $service;

    protected function setUp(): void
    {
        $this->service = new PositionFinder();
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
