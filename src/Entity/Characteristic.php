<?php

namespace App\Entity;

use App\Repository\CharacteristicRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharacteristicRepository::class)]
class Characteristic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\Positive]
    private ?float $height = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    #[Assert\Positive]
    private ?float $tempWeight = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $age = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 20)]
    #[Assert\Type('string')]
    private ?string $sexe = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    #[Assert\Type('string')]
    private ?string $goal = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $fatRate = null;

    #[ORM\Column]
    #[Assert\Type('float')]
    #[Assert\NotBlank]
    private ?float $activityRate = null;

    #[ORM\OneToOne(mappedBy: 'characteristics', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getTempWeight(): ?float
    {
        return $this->tempWeight;
    }

    public function setTempWeight(float $tempWeight): self
    {
        $this->tempWeight = $tempWeight;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getFatRate(): ?int
    {
        return $this->fatRate;
    }

    public function setFatRate(int $fatRate): self
    {
        $this->fatRate = $fatRate;

        return $this;
    }

    public function getActivityRate(): ?float
    {
        return $this->activityRate;
    }

    public function setActivityRate(float $activityRate): self
    {
        $this->activityRate = $activityRate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setCharacteristics(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getCharacteristics() !== $this) {
            $user->setCharacteristics($this);
        }

        $this->user = $user;

        return $this;
    }
}
