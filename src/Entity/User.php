<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(max: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('float')]
    #[Assert\Positive]
    private ?float $height = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    #[Assert\Type('string')]
    private ?string $goal = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    #[Assert\Type('string')]
    private ?string $sexe = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: WeightHistory::class, cascade: ['persist'])]
    private Collection $weight;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('float')]
    #[Assert\Positive]
    private ?float $tempWeight = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Need $need = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('float')]
    private ?float $activityRate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $fatRate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $age = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MealUser::class)]
    private Collection $mealUsers;

    public function __construct()
    {
        $this->weight = new ArrayCollection();
        $this->mealUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * @return Collection<int, WeightHistory>
     */
    public function getWeight(): Collection
    {
        return $this->weight;
    }

    public function addWeight(WeightHistory $weight): self
    {
        if (!$this->weight->contains($weight)) {
            $this->weight->add($weight);
            $weight->setUser($this);
        }

        return $this;
    }

    public function removeWeight(WeightHistory $weight): self
    {
        if ($this->weight->removeElement($weight)) {
            // set the owning side to null (unless already changed)
            if ($weight->getUser() === $this) {
                $weight->setUser(null);
            }
        }

        return $this;
    }

    public function getTempWeight(): ?float
    {
        return $this->tempWeight;
    }

    public function setTempWeight(?float $tempWeight): self
    {
        $this->tempWeight = $tempWeight;

        return $this;
    }

    public function getNeed(): ?Need
    {
        return $this->need;
    }

    public function setNeed(?Need $need): self
    {
        // unset the owning side of the relation if necessary
        if ($need === null && $this->need !== null) {
            $this->need->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($need !== null && $need->getUser() !== $this) {
            $need->setUser($this);
        }

        $this->need = $need;

        return $this;
    }

    public function getActivityRate(): ?float
    {
        return $this->activityRate;
    }

    public function setActivityRate(?float $activityRate): self
    {
        $this->activityRate = $activityRate;

        return $this;
    }

    public function getFatRate(): ?int
    {
        return $this->fatRate;
    }

    public function setFatRate(?int $fatRate): self
    {
        $this->fatRate = $fatRate;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, MealUser>
     */
    public function getMealUsers(): Collection
    {
        return $this->mealUsers;
    }

    public function addMealUser(MealUser $mealUser): self
    {
        if (!$this->mealUsers->contains($mealUser)) {
            $this->mealUsers->add($mealUser);
            $mealUser->setUser($this);
        }

        return $this;
    }

    public function removeMealUser(MealUser $mealUser): self
    {
        if ($this->mealUsers->removeElement($mealUser)) {
            // set the owning side to null (unless already changed)
            if ($mealUser->getUser() === $this) {
                $mealUser->setUser(null);
            }
        }

        return $this;
    }
}
