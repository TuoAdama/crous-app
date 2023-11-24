<?php

namespace App\MessageHandler;

use App\Message\SearchResultMessage;
use App\Repository\SearchResultRepository;
use App\Services\MailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchResultMessageHandler
{

    public function __construct(
        private readonly SearchResultRepository $searchResultRepository,
        private readonly LoggerInterface        $logger,
        private readonly MailService            $mailService,
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
            $this->mailService->sendResultFoundNotification($searchResult);
            $res = count($searchResult->getResults());
            $this->logger->info('{count} results found for criteria id: {id}', [
                'count' => $res,
                'id' => $searchResult->getSearchCriteria()->getId()
            ]);
        }
    }
}