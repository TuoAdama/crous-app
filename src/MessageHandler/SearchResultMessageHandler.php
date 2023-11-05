<?php

namespace App\MessageHandler;

use App\Message\SearchResultMessage;
use App\Repository\SearchResultRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchResultMessageHandler
{

    public function __construct(
        private SearchResultRepository $searchResultRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function __invoke(
        SearchResultMessage $criteriaMessage
    )
    {
        $ids = $criteriaMessage->getIds();
        $searchResults = $this->searchResultRepository->findWhereIdIn($ids);
        foreach ($searchResults as $searchResult) {
            $res = count($searchResult->getResults());
            $this->logger->info('{count} results found for criteria id: {id}', [
                'count' => $res,
                'id' => $searchResult->getSearchCriteria()->getId()
            ]);
        }
    }
}