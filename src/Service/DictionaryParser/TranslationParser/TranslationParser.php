<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser\TranslationParser;

use App\Entity\DictionaryParser\DictionaryElement;
use App\Exception\Entity\DictionaryParser\DictionaryElementInvalidTypeException;

use function array_filter;
use function array_map;
use function preg_split;
use function trim;

final class TranslationParser implements TranslationParserInterface
{
    /**
     * @throws DictionaryElementInvalidTypeException
     * @return DictionaryElement[]
     */
    public function parse(string $translation): array
    {
        $words    = [];
        $variants = array_filter(preg_split('/\d\) /', $translation), static fn (string $word): bool => (bool)$word);
        foreach ($variants as $variant) {
            $words[] = self::createDictionaryElement($variant);
        }

        /** @psalm-var DictionaryElement[] */
        return $words;
    }

    /**
     * @throws DictionaryElementInvalidTypeException
     */
    private static function createDictionaryElement(string $translation): DictionaryElement
    {
        $words = array_map(
            static fn (string $word): string => trim($word),
            preg_split(
                '/; (?=(([^"]*"){2})*[^"]*$)(?![^(]*\))|, (?=(([^"]*"){2})*[^"]*$)(?![^(]*\))/',
                $translation
            )
        );

        return new DictionaryElement(DictionaryElement::TRANSLATION_TYPE, $words);
    }
}
