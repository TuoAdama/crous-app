<?php

namespace App\Services;

use App\Entity\SmsMessage;
use App\Repository\SmsMessageRepository;

class SmsMessageService
{

    public function __construct(
        private SmsMessageRepository $smsMessageRepository
    )
    {
    }

    function getAllUnsentSmsMessages(): array
    {
        $this->smsMessageRepository->getAllUnsentSmsMessages();
        return [];
    }

    public function store(SmsMessage $smsMessage): SmsMessage
    {
        return null;
    }
}