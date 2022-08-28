<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TranslationParser;

use App\Service\DictionaryParser\TranslationParser\TranslationParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingConstructor
 * @internal
 */
final class SimpleParserUnitTest extends KernelTestCase
{
    private TranslationParser $service;

    protected function setUp(): void
    {
        $this->service = new TranslationParser();
    }

    public function testParseSimpleStringReturnValid(): void
    {
        $testString = 'нелюбовь, неприязнь, нерасположение, антипатия (for, of, to)';

        $expected = ['нелюбовь', 'неприязнь', 'нерасположение', 'антипатия (for, of, to)'];

        self::assertEquals($expected, $this->service->parse($testString));
    }

    public function testParseSimpleStringWithSemicolonReturnValid(): void
    {
        $testString = 'нелюбовь, неприязнь; нерасположение; антипатия (for, of, to)';

        $expected = ['нелюбовь', 'неприязнь', 'нерасположение', 'антипатия (for, of, to)'];
        self::assertEquals($expected, $this->service->parse($testString));
    }

    public function testParseTwoValuesStringReturnValid(): void
    {
        $testString = '1) периодичность, частота 2) _физиол. менструации';

        $expected = ['периодичность', 'частота', '_физиол. менструации'];

        self::assertEquals($expected, $this->service->parse($testString));
    }
}
