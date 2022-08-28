<?php

declare(strict_types=1);

namespace App\Tests\Service\DictionaryParser;

use App\Exception\DictionaryParser\UndefinedTextDictionaryTypeException;
use App\Service\DictionaryParser\TextTypeDefiner;
use App\Service\DictionaryParser\TypeParser\SimpleParser;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-suppress MissingConstructor
 */
final class TextTypeDefinerUnitTest extends TestCase
{
    private TextTypeDefiner $service;

    protected function setUp(): void
    {
        $this->service = new TextTypeDefiner();
    }

    /**
     * @throws UndefinedTextDictionaryTypeException
     */
    public function testDefineReturnArabian(): void
    {
        $source = "periodical [,pIэrI'OdIkэl] 1. _a. периодический; появляющийся через определённые промежутки времени; выпускаемый через определённые промежутки времени 2. _n. периодическое издание, журнал";

        self::assertEquals('arabian', $this->service->define($source));
    }

    /**
     * @throws UndefinedTextDictionaryTypeException
     */
    public function testDefineReturnSimple(): void
    {
        $source = "beehive ['bi:haIv] _n. улей";

        self::assertEquals(SimpleParser::class, $this->service->define($source));
    }

    /**
     * @throws UndefinedTextDictionaryTypeException
     */
    public function testDefineReturnRomanian(): void
    {
        $source = "bat I [bЭt] _n. летучая мышь *) to have bats in one's belfry _разг. быть ненормальным; to go bats сходить с ума; like a bat out of hell очень быстро, со всех ног; blind as a bat совершенно слепой II [bЭt] 1. _n. 1) дубина; било (для льна); бита (в крикете); лапта; _редк. ракетка (для тенниса) 2) = batsman; a good bat хороший крикетист";

        self::assertEquals('romanian', $this->service->define($source));
    }

    public function testDefineThrowUndefinedException(): void
    {
        $source = "bat [bЭt] летучая мышь *) to have bats in one's belfry _разг. быть ненормальным";

        self::expectException(UndefinedTextDictionaryTypeException::class);

        $this->service->define($source);
    }
}
