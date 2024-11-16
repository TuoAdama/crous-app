<?php

namespace App\DTO\Response;

use App\Entity\SearchCriteria;
use JsonSerializable;

class CriteriaResultResponse implements JsonSerializable
{

    private SearchCriteria $criteria;
    private ?string $link;

    public function __construct(SearchCriteria $searchCriteria)
    {
        $this->criteria = $searchCriteria;
    }

    /**
     * @return SearchCriteria
     */
    public function getCriteria(): SearchCriteria
    {
        return $this->criteria;
    }

    /**
     * @param string|null $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }


    public function jsonSerialize(): array
    {
        return [
            'criteria' => $this->criteria,
            'link' => $this->link ?? null,
        ];
    }
}