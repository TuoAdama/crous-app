<?php

namespace App\Entity;

use App\Repository\SearchCriteriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SearchCriteriaRepository::class)]
class SearchCriteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    #[Assert\NotBlank]
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
}
