<?php

declare(strict_types=1);

namespace App\Entity\DictionaryParser;

final class DictionaryWord
{
    private string $source;

    private string $position;

    private string $transcription;

    /**
     * @var string[]
     */
    private array $translation;

    /**
     * @param string[] $translation
     */
    public function __construct(string $source, string $position, string $transcription, array $translation)
    {
        $this->source        = $source;
        $this->position      = $position;
        $this->transcription = $transcription;
        $this->translation   = $translation;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getTranscription(): string
    {
        return $this->transcription;
    }

    /**
     * @return string[]
     */
    public function getTranslation(): array
    {
        return $this->translation;
    }
}
