<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use JetBrains\PhpStorm\Pure;

use function sprintf;

final class UndefinedTextDictionaryTypeException extends DictionaryParserException
{
    /** @psalm-suppress UndefinedAttributeClass */
    #[Pure]
    public static function inText(string $text): self
    {
        return new self(sprintf('%s', $text));
    }
}
