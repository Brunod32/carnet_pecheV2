<?php

namespace App\Entity;

use App\Repository\FishingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FishingRepository::class)]
class Fishing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $fishRace;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'string', length: 50)]
    private $place;

    #[ORM\Column(type: 'string', length: 50)]
    private $lure;

    #[ORM\Column(type: 'string', length: 50)]
    private $weather;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $bait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFishRace(): ?string
    {
        return $this->fishRace;
    }

    public function setFishRace(string $fishRace): self
    {
        $this->fishRace = $fishRace;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getLure(): ?string
    {
        return $this->lure;
    }

    public function setLure(string $lure): self
    {
        $this->lure = $lure;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getBait(): ?string
    {
        return $this->bait;
    }

    public function setBait(string $bait): self
    {
        $this->bait = $bait;

        return $this;
    }
}
