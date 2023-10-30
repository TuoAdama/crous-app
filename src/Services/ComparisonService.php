<?php

namespace App\Services;

use App\Entity\SearchCriteria;
use App\Repository\SearchResultRepository;

class ComparisonService
{

    public function __construct(
        private SearchResultRepository $searchResultRepository
    )
    {
    }

    function exists(SearchCriteria $criteria, array $items): bool
    {
        $result = $this->searchResultRepository->getResultAvailable($criteria);
        if ($result == null) {
            return false;
        }
        $newItemsIds = [];
        foreach ($items as $item) {
            $newItemsIds[] = $item['id'];
        }
        $oldItemsIds = $result->getItemsIds();

        return array_diff($newItemsIds, $oldItemsIds) == array_diff($oldItemsIds, $newItemsIds);
    }
}