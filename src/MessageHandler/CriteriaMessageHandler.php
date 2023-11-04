<?php

namespace App\MessageHandler;

use App\Message\CriteriaMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CriteriaMessageHandler
{
    public function __invoke(
        CriteriaMessage $criteriaMessage
    )
    {
        
    }
}