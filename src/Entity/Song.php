<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SongRepository::class)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        minMessage: 'Votre nom doit contenir au moins 3 caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\Type(
        type: 'integer',
        message: 'Le format est incorrect.',
    )]
    #[Assert\Regex('/^[0-9]\d*$/')]
    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
