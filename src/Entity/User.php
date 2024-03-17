<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
#[UniqueEntity('number')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotNull]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotNull]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 3)]
    private ?string $username = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SearchCriteria::class)]
    private Collection $searchCriterias;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Regex(pattern: "/0[1-9]{1}[0-9]{8}/", message: "Invalid number")]
    private ?string $number = null;

    #[ORM\Column(nullable: true)]
    private ?bool $number_is_verified = null;

    #[ORM\Column(nullable: true)]
    private ?int $TemporaryNumberCode = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $TemporaryCodeExpiredAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number_token_verification = null;

    #[ORM\Column(nullable: true)]
    private ?bool $emailIsVerified = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailTokenVerification = null;

    public function __construct()
    {
        $this->searchCriterias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, SearchCriteria>
     */
    public function getSearchCriterias(): Collection
    {
        return $this->searchCriterias;
    }

    public function addSearchCriteria(SearchCriteria $searchCriteria): static
    {
        if (!$this->searchCriterias->contains($searchCriteria)) {
            $this->searchCriterias->add($searchCriteria);
            $searchCriteria->setUser($this);
        }

        return $this;
    }

    public function removeSearchCriteria(SearchCriteria $searchCriteria): static
    {
        if ($this->searchCriterias->removeElement($searchCriteria)) {
            // set the owning side to null (unless already changed)
            if ($searchCriteria->getUser() === $this) {
                $searchCriteria->setUser(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function isNumberIsVerified(): ?bool
    {
        return $this->number_is_verified;
    }

    public function setNumberIsVerified(?bool $number_is_verified): static
    {
        $this->number_is_verified = $number_is_verified;

        return $this;
    }

    public function getTemporaryNumberCode(): ?int
    {
        return $this->TemporaryNumberCode;
    }

    public function setTemporaryNumberCode(?int $TemporaryNumberCode): static
    {
        $this->TemporaryNumberCode = $TemporaryNumberCode;

        return $this;
    }

    public function getTemporaryCodeExpiredAt(): ?\DateTimeImmutable
    {
        return $this->TemporaryCodeExpiredAt;
    }

    public function setTemporaryCodeExpiredAt(?\DateTimeImmutable $TemporaryCodeExpiredAt): static
    {
        $this->TemporaryCodeExpiredAt = $TemporaryCodeExpiredAt;

        return $this;
    }

    public function getNumberTokenVerification(): ?string
    {
        return $this->number_token_verification;
    }

    public function setNumberTokenVerification(?string $number_token_verification): static
    {
        $this->number_token_verification = $number_token_verification;

        return $this;
    }

    public function isEmailIsVerified(): ?bool
    {
        return $this->emailIsVerified;
    }

    public function setEmailIsVerified(?bool $emailIsVerified): static
    {
        $this->emailIsVerified = $emailIsVerified;

        return $this;
    }

    public function getEmailTokenVerification(): ?string
    {
        return $this->emailTokenVerification;
    }

    public function setEmailTokenVerification(?string $emailTokenVerification): static
    {
        $this->emailTokenVerification = $emailTokenVerification;

        return $this;
    }
}
