<?php

namespace App\MessageHandler;

use App\Message\FindCriteriaResultMessage;
use App\Services\SearchService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FindCriteriaResultMessageHandler
{

    public function __construct(
        private readonly SearchService $searchService,
    )
    {
    }

    public function __invoke(FindCriteriaResultMessage $message): void{
        $this->searchService->run();
    }
}