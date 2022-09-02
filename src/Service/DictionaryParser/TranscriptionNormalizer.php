<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

final class TranscriptionNormalizer
{
    private const MAP = [
        'Ф' => 'θ',
        'tS' => 'ʧ',
        'I' => 'ɪ',
        'э' => 'ə',
        'Э' => 'æ',
        'O' => 'ɔ',
        'dз' => 'ʤ',
        'A' => 'ʌ',
        'N' => 'ŋ',
        'S' => 'ʃ',
        'E' => 'ɛ',
        "'" => 'ˈ',
    ];

    public function normalize(string $transcription): string
    {
        return strtr($transcription, self::MAP);
    }
}
