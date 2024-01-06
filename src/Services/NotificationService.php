<?php

namespace App\Services;

use App\Entity\SearchResult;

class NotificationService
{
    public function __construct(
        private readonly MailService $mailService,
        private readonly SmsInterface $smsService
    )
    {
    }

    public function notify(SearchResult $searchResult)
    {
        $criteria = $searchResult->getSearchCriteria();
        //$user = $criteria->getUser();
        //TODO Verifier les options choisies par les utilisateurs: si sms -> envoi sms, si mail -> envoi mail
        $this->mailService->sendResultFoundNotification($searchResult);
        //$this->smsService->send()
    }
}