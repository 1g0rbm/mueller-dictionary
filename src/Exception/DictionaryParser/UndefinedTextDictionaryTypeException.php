<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use JetBrains\PhpStorm\Pure;

use function sprintf;

/** @psalm-suppress UndefinedAttributeClass */
final class UndefinedTextDictionaryTypeException extends DictionaryParserException
{
    #[Pure]
    public static function inText(string $text): self
    {
        return new self(sprintf('%s', $text));
    }
}
