<?php

declare(strict_types=1);

namespace App\Exception\Entity\DictionaryParser;

use App\Exception\DictionaryParser\DictionaryParserException;
use JetBrains\PhpStorm\Pure;

use function sprintf;

/** @psalm-suppress UndefinedAttributeClass */
final class DictionaryElementInvalidTypeException extends DictionaryParserException
{
    #[Pure]
    public static function byType(string $type): self
    {
        return new self(sprintf('Unavailable dictionary element type "%s"', $type));
    }
}
