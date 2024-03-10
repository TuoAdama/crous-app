<?php

namespace App\Services\Token;

use App\Entity\User;
use DateInterval;
use DateTimeImmutable;

class SmsTokenValidatorService implements SmsTokenValidator
{



    public function isValid(User $user, $token): bool
    {
        $userToken = $user->getNumberTokenVerification();
        if ($userToken != $token){
            return false;
        }
        list($header, $payload) = explode('.', $userToken);
        $payload = json_decode(base64_decode($payload), true);
        $now = (new DateTimeImmutable())->getTimestamp();

        if ($now > $payload['exp']){
            return false;
        }
        return true;
    }
}