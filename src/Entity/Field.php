<?php

namespace App\Entity;

use App\Enum\FieldType;
use App\Repository\FieldRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private Table $owner;

    #[ORM\Column(enumType: FieldType::class)]
    private FieldType $type;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $foreignKeyExtra = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): Table
    {
        return $this->owner;
    }

    public function setOwner(Table $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getType(): FieldType
    {
        return $this->type;
    }

    public function setType(FieldType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getForeignKeyExtra(): ?string
    {
        return $this->foreignKeyExtra;
    }

    public function setForeignKeyExtra(?string $foreignKeyExtra): static
    {
        $this->foreignKeyExtra = $foreignKeyExtra;

        return $this;
    }
}
