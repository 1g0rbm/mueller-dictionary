<?php

declare(strict_types=1);

namespace App\Entity\DictionaryParser;

use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;

final class DictionaryElement
{
    public const SOURCE_TYPE = 'source';
    public const TRANSCRIPTION_TYPE = 'transcription';
    public const POSITION_TYPE = 'position';
    public const TRANSLATION_TYPE = 'translation';

    private const TYPES = [
        self::SOURCE_TYPE,
        self::TRANSCRIPTION_TYPE,
        self::POSITION_TYPE,
        self::TRANSLATION_TYPE,
    ];

    private string $type;

    private string|array $value;

    /**
     * @param string|string[] $value
     *
     * @throws DictionaryElementInvalidTypeException
     */
    public function __construct(string $type, string|array $value)
    {
        if (!\in_array($type, self::TYPES, true)) {
            throw DictionaryElementInvalidTypeException::byType($type);
        }

        $this->type  = $type;
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string|array
    {
        return $this->value;
    }
}
