<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

final class PartOfSpeechNormalizer
{
    private const MAP = [
        '_a.' => 'adjective',
        '_adv.' => 'adverb',
        '_attr.' => 'attributively',
        '_int.' => 'interjection',
        '_prep.' => 'preposition',
        '_suff.' => 'suffix',
        '_pres.' => 'present',
        '_v.' => 'verb',
        '_sup.' => 'superlative',
        '_pref.' => 'prefix',
        '_n.' => 'noun',
        '_pron.' => 'pronoun',
        '_poss.' => 'possessive (pronoun)',
        '_demonstr.' => 'demonstrative (pronoun)',
        '_cj.' => 'conjunction',
        '_p.' => 'past indefinite',
        '_pl.' => 'plural',
        '_obj.' => 'objective case',
        '_comp.' => 'comparative degree',
    ];

    public function normalize(string $pos): string
    {
        return strtr($pos, self::MAP);
    }
}
