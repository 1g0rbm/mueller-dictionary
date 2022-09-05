<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function array_filter;
use function array_map;
use function preg_split;
use function trim;

final class PartOfSpeechSplitter
{
    private PartOfSpeechFinder $posFinder;

    public function __construct(PartOfSpeechFinder $partOfSpeechFinder)
    {
        $this->posFinder = new PartOfSpeechFinder();
    }

    /**
     * @return string[]
     */
    public function split(string $string): array
    {
        $possiblePos = $this->posFinder->find($string);

        $parts =  array_map(
            static fn (string $part): string => trim($part),
            array_filter(preg_split('/\d\./', $string))
        );

        if (isset($parts[0]) && $possiblePos === $parts[0]) {
            return array_map(
                static fn (string $string): string => "{$possiblePos} {$string}",
                array_filter($parts, static fn (string $string): bool => $string !== $possiblePos)
            );
        }

        return $parts;
    }
}
