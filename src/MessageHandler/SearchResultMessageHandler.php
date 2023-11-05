<?php

namespace App\MessageHandler;

use App\Message\SearchResultMessage;
use App\Repository\SearchCriteriaRepository;
use App\Repository\SearchResultRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchResultMessageHandler
{

    public function __construct(
        private SearchResultRepository $criteriaRepository,
    )
    {
    }

    public function __invoke(
        SearchResultMessage $criteriaMessage
    )
    {
        $ids = $criteriaMessage->getId();
        dd($ids);
    }
}