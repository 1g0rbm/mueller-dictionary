<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use JetBrains\PhpStorm\Pure;

/** @psalm-suppress UndefinedAttributeClass */
final class DictionaryFileNotFoundException extends DictionaryParserException
{
    #[Pure]
    public static function byPath(string $path): self
    {
        return new self(sprintf('There is no file by path "%s"', $path));
    }
}
