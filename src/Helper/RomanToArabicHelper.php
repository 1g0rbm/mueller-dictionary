<?php

declare(strict_types=1);

namespace App\Helper;

final class RomanToArabicHelper
{
    public const MAP = [
        'I' => 1,
        'II' => 2,
        'III' => 3,
        'IV' => 4,
        'V' => 5,
        'VI' => 6,
        'VII' => 7,
        'VIII' => 8,
        'IX' => 9,
        'X' => 10,
    ];

    public static function translate(string $romanNumber): ?int
    {
        return self::MAP[$romanNumber] ?? null;
    }
}
