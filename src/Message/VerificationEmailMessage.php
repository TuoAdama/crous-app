<?php

namespace App\Message;

use App\Enum\EmailVerificationType;

class VerificationEmailMessage
{
    private EmailVerificationType $type = EmailVerificationType::CHANGE_EMAIL;

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

    /**
     * @return EmailVerificationType
     */
    public function getType(): EmailVerificationType
    {
        return $this->type;
    }


    /**
     * @param EmailVerificationType $type
     */
    public function setType(EmailVerificationType $type): void
    {
        $this->type = $type;
    }
}