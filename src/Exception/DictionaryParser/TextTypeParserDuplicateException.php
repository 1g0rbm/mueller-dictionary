<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use JetBrains\PhpStorm\Pure;

/** @psalm-suppress UndefinedAttributeClass */
final class TextTypeParserDuplicateException extends DictionaryParserException
{
    #[Pure]
    public static function byType(string $type): self
    {
        return new self(sprintf('Text parser with type "%s" already exists.', $type));
    }
}
