<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser\TranslationParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;
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

    /**
     * @throws DictionaryElementInvalidTypeException
     */
    public function testParseSimpleStringReturnValid(): void
    {
        $testString = 'нелюбовь, неприязнь, нерасположение, антипатия (for, of, to)';

        $expected = new DictionaryElement(
            DictionaryElement::TRANSLATION_TYPE,
            ['нелюбовь', 'неприязнь', 'нерасположение', 'антипатия (for, of, to)']
        );

        self::assertEquals([$expected], $this->service->parse($testString));
    }

    /**
     * @throws DictionaryElementInvalidTypeException
     */
    public function testParseSimpleStringWithSemicolonReturnValid(): void
    {
        $testString = 'нелюбовь, неприязнь; нерасположение; антипатия (for, of, to)';

        $expected = new DictionaryElement(
            DictionaryElement::TRANSLATION_TYPE,
            ['нелюбовь', 'неприязнь', 'нерасположение', 'антипатия (for, of, to)']
        );

        self::assertEquals([$expected], $this->service->parse($testString));
    }

    /**
     * @throws DictionaryElementInvalidTypeException
     */
    public function testParseTwoValuesStringReturnValid(): void
    {
        $testString = '1) периодичность, частота 2) _физиол. менструации';

        $expected = [
            new DictionaryElement(
                DictionaryElement::TRANSLATION_TYPE,
                ['периодичность', 'частота']
            ),
            new DictionaryElement(
                DictionaryElement::TRANSLATION_TYPE,
                ['_физиол. менструации']
            ),
        ];

        self::assertEquals($expected, $this->service->parse($testString));
    }
}
