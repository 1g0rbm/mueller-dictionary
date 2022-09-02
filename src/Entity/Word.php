<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\DictionaryParser\DictionaryWord;
use App\Repository\WordRepository;
use Doctrine\ORM\Mapping as ORM;

/** @psalm-suppress MissingConstructor */
#[ORM\Table(name: 'words')]
#[ORM\Entity(repositoryClass: WordRepository::class)]
final class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $source;

    #[ORM\Column(length: 100)]
    private string $pos;

    #[ORM\Column(length: 100)]
    private string $transcription;

    /** @var string[] */
    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    private array $translations;

    public static function createFromDto(DictionaryWord $dto): self
    {
        $word = new self();
        $word->setSource($dto->getSource());
        $word->setPos($dto->getPosition());
        $word->setTranscription($dto->getTranscription());
        $word->setTranslations($dto->getTranslation());

        return $word;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getPos(): string
    {
        return $this->pos;
    }

    public function setPos(string $pos): self
    {
        $this->pos = $pos;

        return $this;
    }

    public function getTranscription(): string
    {
        return $this->transcription;
    }

    public function setTranscription(string $transcription): self
    {
        $this->transcription = $transcription;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param string[] $translations
     */
    public function setTranslations(array $translations): void
    {
        $this->translations = $translations;
    }
}
