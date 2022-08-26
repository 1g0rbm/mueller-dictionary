<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use function fgets;
use function implode;
use function trim;

final class WordsReader
{
    /**
     * @param resource $fp
     *
     * @return string[]
     */
    public function read($fp, int $count = 10): array
    {
        $words = [];
        /** @var string[] $word */
        $word = [];
        while (!feof($fp) && \count($words) < $count) {
            $line = self::readLine($fp);
            if (!$line) {
                continue;
            }

            if ($line === '_____') {
                $words = self::saveWord($words, $word);
                $word = [];
                continue;
            }

            $word[] = $line;
        }

        /** @psalm-var string[] */
        return $words;
    }

    /**
     * @param string[] $acc
     * @param string[] $word
     *
     * @return string[]
     */
    private function saveWord(array $acc, array $word): array
    {
        if (empty($word)) {
            return $acc;
        }

        $acc[] = implode(' ', $word);

        return $acc;
    }

    /**
     * @param resource $fp
     */
    private function readLine($fp): false|string
    {
        $line = fgets($fp);

        if (!$line) {
            return false;
        }

        return trim($line);
    }
}
