<?php

namespace App\Services\Token;

use App\Entity\User;
use DateInterval;
use DateTimeImmutable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class SmsTokenValidatorService implements SmsTokenValidator
{

    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function validate(User $user, $token): void
    {
        $userToken = $user->getNumberTokenVerification();
        if ($userToken != $token){
            throw new NotFoundHttpException($this->translator->trans("page.notfound"));
        }
        list($header, $payload) = explode('.', $userToken);
        $payload = json_decode(base64_decode($payload), true);
        $now = (new DateTimeImmutable())->getTimestamp();

        if ($now > $payload['exp']){
            throw new NotFoundHttpException($this->translator->trans("token.expired"));
        }
    }
}