<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TranslationParser;

use function array_filter;
use function array_map;
use function preg_split;
use function trim;

final class TranslationParser implements TranslationParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(string $translation): array
    {
        $words = [];
        $variants = array_filter(
            preg_split('/\d\) /', $translation),
            static fn (string $word): bool => (bool)$word
        );
        foreach ($variants as $variant) {
            $words = array_merge($words, self::createDictionaryElement($variant));
        }

        /** @psalm-var string[] */
        return $words;
    }

    /**
     * @return string[]
     */
    private static function createDictionaryElement(string $translation): array
    {
        return array_map(
            static fn (string $word): string => trim($word),
            preg_split(
                '/; (?=(([^"]*"){2})*[^"]*$)(?![^(]*\))/',
                $translation
            )
        );
    }
}
