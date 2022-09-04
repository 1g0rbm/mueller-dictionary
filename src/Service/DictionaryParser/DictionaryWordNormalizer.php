<?php

declare(strict_types=1);

namespace App\Service\DictionaryParser;

use App\Entity\DictionaryParser\DictionaryWord;

final class DictionaryWordNormalizer
{
    private TranscriptionNormalizer $transcriptionNormalizer;

    private PartOfSpeechNormalizer $posNormalizer;

    public function __construct(TranscriptionNormalizer $transcriptionNormalizer, PartOfSpeechNormalizer $posNormalizer)
    {
        $this->transcriptionNormalizer = $transcriptionNormalizer;
        $this->posNormalizer          = $posNormalizer;
    }

    public function normalize(DictionaryWord $word): DictionaryWord
    {
        return new DictionaryWord(
            $word->getSource(),
            $this->posNormalizer->normalize($word->getPosition()),
            $this->transcriptionNormalizer->normalize($word->getTranscription()),
            $word->getTranslation()
        );
    }
}
