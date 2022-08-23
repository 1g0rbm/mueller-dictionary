<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use JetBrains\PhpStorm\Pure;

/** @psalm-suppress UndefinedAttributeClass */
final class ParsingPartNotFoundException extends DictionaryParserException
{
    #[Pure]
    public static function transcription(string $text): self
    {
        return new self(sprintf('Transcription not found in string "%s"', $text));
    }

    #[Pure]
    public static function position(string $text): self
    {
        return new self(sprintf('Position not found in string "%s"', $text));
    }
}
