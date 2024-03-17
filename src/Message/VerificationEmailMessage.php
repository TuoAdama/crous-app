<?php

namespace App\Message;

class VerificationEmailMessage
{
    public function __construct(
        private readonly int $userId
    )
    {
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}