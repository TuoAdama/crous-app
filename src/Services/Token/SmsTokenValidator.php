<?php

namespace App\Services\Token;

use App\Entity\User;

interface SmsTokenValidator
{
    public function isValid(User $user, $token): bool;
}