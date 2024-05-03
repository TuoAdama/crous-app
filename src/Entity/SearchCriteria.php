<?php

namespace App\Entity;

use App\Repository\SearchCriteriaRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SearchCriteriaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SearchCriteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    private array $location = [];

    #[ORM\Column(type: Types::JSON)]
    #[Assert\NotBlank]
    private array $type = [];

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: "integer")]
    #[Assert\Positive]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'searchCriteria', targetEntity: SearchResult::class)]
    private Collection $searchResults;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'searchCriterias')]
    private ?User $user = null;

    public function __construct()
    {
        $this->searchResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): array
    {
        return $this->location;
    }

    public function setLocation(array $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

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
     * @return Collection<int, SearchResult>
     */
    public function getSearchResults(): Collection
    {
        return $this->searchResults;
    }

    public function addSearchResult(SearchResult $searchResult): static
    {
        if (!$this->searchResults->contains($searchResult)) {
            $this->searchResults->add($searchResult);
            $searchResult->setSearchCriteria($this);
        }

        return $this;
    }

    public function removeSearchResult(SearchResult $searchResult): static
    {
        if ($this->searchResults->removeElement($searchResult)) {
            // set the owning side to null (unless already changed)
            if ($searchResult->getSearchCriteria() === $this) {
                $searchResult->setSearchCriteria(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLat1(): float
    {
        return $this->getExtent()[0];
    }

    public function getLon1(): float
    {
        return $this->getExtent()[1];
    }

    public function getLat2(): float
    {
        return $this->getExtent()[2];
    }

    public function getLon2(): float
    {
        return $this->getExtent()[3];
    }


    public function getExtent(): array
    {
        return $this->getLocation()['properties']['extent'];
    }


    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new DateTimeImmutable();
        if ($this->createdAt == null){
            $this->createdAt = $now;
        }
        $this->updatedAt = $now;
    }
}
