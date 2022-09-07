<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Entity\DictionaryParser\DictionaryWord;
use App\Repository\WordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/** @psalm-suppress MissingConstructor */
#[ORM\Table(name: 'words')]
#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'words:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'words:item']]],
    attributes: [
        'pagination_items_per_page' => 10
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    properties: ['source' => 'start']
)]
final class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['words:list', 'words:item'])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(['words:list', 'words:item'])]
    private string $source;

    #[ORM\Column(length: 100)]
    #[Groups(['words:list', 'words:item'])]
    private string $pos;

    #[ORM\Column(length: 100)]
    #[Groups(['words:list', 'words:item'])]
    private string $transcription;

    /** @var string[] */
    #[ORM\Column(type: 'json', options: ['jsonb' => true])]
    #[Groups(['words:list', 'words:item'])]
    private array $translations;

    public static function createFromDto(DictionaryWord $dto): self
    {
        $word = new self();
        $word->setSource($dto->getSource())
            ->setPos($dto->getPosition())
            ->setTranscription($dto->getTranscription())
            ->setTranslations($dto->getTranslation());

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
