<?php

namespace App\Entity;

use App\Repository\AthleteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AthleteRepository::class)]
class Athlete
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $birthDate = null;

    #[ORM\Column]
    private ?int $code = null;

    #[ORM\ManyToMany(targetEntity: TitlesWon::class, inversedBy: 'athletes')]
    private Collection $titlesName;

    public function __construct()
    {
        $this->titlesName = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getSport(): ?string
    {
        return $this->sport;
    }

    public function setSport(?string $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function setBirthDate(?string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, TitlesWon>
     */
    public function getTitlesName(): Collection
    {
        return $this->titlesName;
    }

    public function addTitlesName(TitlesWon $titlesName): self
    {
        if (!$this->titlesName->contains($titlesName)) {
            $this->titlesName->add($titlesName);
        }

        return $this;
    }

    public function removeTitlesName(TitlesWon $titlesName): self
    {
        $this->titlesName->removeElement($titlesName);

        return $this;
    }
}
