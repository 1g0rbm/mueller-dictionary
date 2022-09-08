<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Service\DictionaryParser\MeaningSplitter;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @group unit
 */
final class MeaningSplitterUnitTest extends TestCase
{
    private MeaningSplitter $service;

    protected function setUp(): void
    {
        $this->service = new MeaningSplitter();
    }

    public function testSplitReturnSuccess(): void
    {
        $testString = 'I [bIэ] _n. пиво; small beer а) слабое пиво; б) _перен. пустяки; в) _перен. ничтожный человек; to think no small beer of oneself быть о себе высокого мнения *) beer and skittles праздные развлечения; to be in beer _разг. быть выпивши; beer chaser _разг. "прицеп" (стакан пива вслед за виски) II [bIэ] _n. _текст. ход (основы)';
        $result     = $this->service->split($testString);

        self::assertCount(2, $result);

        $firstResultExpected = '[bIэ] _n. пиво; small beer а) слабое пиво; б) _перен. пустяки; в) _перен. ничтожный человек; to think no small beer of oneself быть о себе высокого мнения *) beer and skittles праздные развлечения; to be in beer _разг. быть выпивши; beer chaser _разг. "прицеп" (стакан пива вслед за виски)';
        self::assertEquals($firstResultExpected, $result[0]);

        $secondResultExpected = '[bIэ] _n. _текст. ход (основы)';
        self::assertEquals($secondResultExpected, $result[1]);
    }
}
