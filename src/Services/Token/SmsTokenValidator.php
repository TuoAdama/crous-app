<?php

namespace App\Services\Token;

use App\Entity\User;

interface SmsTokenValidator
{
    public function validate(User $user, $token): void;
}