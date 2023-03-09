<?php

namespace App\Entity;

use App\Repository\TitlesWonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TitlesWonRepository::class)]
class TitlesWon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titles = null;

    #[ORM\ManyToMany(targetEntity: Athlete::class, mappedBy: 'titlesName')]
    private Collection $athletes;

    public function __construct()
    {
        $this->athletes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitles(): ?string
    {
        return $this->titles;
    }

    public function setTitles(?string $titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    /**
     * @return Collection<int, Athlete>
     */
    public function getAthletes(): Collection
    {
        return $this->athletes;
    }

    public function addAthlete(Athlete $athlete): self
    {
        if (!$this->athletes->contains($athlete)) {
            $this->athletes->add($athlete);
            $athlete->addTitlesName($this);
        }

        return $this;
    }

    public function removeAthlete(Athlete $athlete): self
    {
        if ($this->athletes->removeElement($athlete)) {
            $athlete->removeTitlesName($this);
        }

        return $this;
    }
}
