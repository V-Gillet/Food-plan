<?php

namespace App\Entity;

use App\Repository\NeedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeedRepository::class)]
class Need
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $lipid = null;

    #[ORM\Column(nullable: true)]
    private ?int $carb = null;

    #[ORM\Column(nullable: true)]
    private ?int $protein = null;

    #[ORM\Column(nullable: true)]
    private ?int $maintenanceCalory = null;

    #[ORM\Column(nullable: true)]
    private ?int $gainCalory = null;

    #[ORM\Column(nullable: true)]
    private ?int $lossCalory = null;

    #[ORM\OneToOne(inversedBy: 'need', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLipid(): ?int
    {
        return $this->lipid;
    }

    public function setLipid(?int $lipid): self
    {
        $this->lipid = $lipid;

        return $this;
    }

    public function getCarb(): ?int
    {
        return $this->carb;
    }

    public function setCarb(?int $carb): self
    {
        $this->carb = $carb;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(?int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getMaintenanceCalory(): ?int
    {
        return $this->maintenanceCalory;
    }

    public function setMaintenanceCalory(?int $maintenanceCalory): self
    {
        $this->maintenanceCalory = $maintenanceCalory;

        return $this;
    }

    public function getGainCalory(): ?int
    {
        return $this->gainCalory;
    }

    public function setGainCalory(?int $gainCalory): self
    {
        $this->gainCalory = $gainCalory;

        return $this;
    }

    public function getlossCalory(): ?int
    {
        return $this->lossCalory;
    }

    public function setlossCalory(?int $lossCalory): self
    {
        $this->lossCalory = $lossCalory;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
