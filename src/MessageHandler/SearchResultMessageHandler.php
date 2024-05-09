<?php

namespace App\MessageHandler;

use App\Message\SearchResultMessage;
use App\Repository\SearchResultRepository;
use App\Services\NotificationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchResultMessageHandler
{

    public function __construct(
        private readonly SearchResultRepository $searchResultRepository,
        private readonly LoggerInterface        $logger,
        private readonly NotificationService     $notificationService,
    )
    {
    }

    public function __invoke(
        SearchResultMessage $criteriaMessage
    ): void
    {
        $ids = $criteriaMessage->getIds();
        $searchResults = $this->searchResultRepository->findWhereIdIn($ids);
        foreach ($searchResults as $searchResult) {
            $this->notificationService->notify($searchResult);
            $res = count($searchResult->getResults());
            $this->logger->info('{count} results found for criteria id: {id}', [
                'count' => $res,
                'id' => $searchResult->getSearchCriteria()->getId()
            ]);
        }
    }
}