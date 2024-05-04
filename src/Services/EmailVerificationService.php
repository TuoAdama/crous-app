<?php

namespace App\Services;

use App\Entity\User;
use App\Enum\EmailVerificationType;
use App\Message\VerificationEmailMessage;
use App\Repository\UserRepository;
use App\Services\Token\TokenGenerator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;

class EmailVerificationService
{

    public function __construct(
        private readonly TokenGenerator $tokenGenerator,
        private readonly ExpirationService $expirationService,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus,
        private readonly UserRepository $userRepository,
    )
    {

    }

    /**
     * @throws Exception
     */
    public function notify(User $user, EmailVerificationType $type = EmailVerificationType::CHANGE_EMAIL): void
    {
        $expiredAt = $this->expirationService->getExpiredDate('email.verification.token.expired');
        $token = $this->tokenGenerator
            ->setPayload([
                'exp' => $expiredAt->getTimestamp(),
                'sub' => $user->getId()
            ])
            ->generate();
        $user->setEmailIsVerified(false)
            ->setEmailTokenVerification($token);
        $this->entityManager->flush();
        $verificationMessage = new VerificationEmailMessage($user->getId());
        $verificationMessage->setType($type);
        $this->bus->dispatch($verificationMessage);
    }

    /**
     * @param string $token
     * @return bool
     */
    public function tokenIsValid(string $token): bool
    {
        $user = $this->userRepository->findOneBy([
            'emailTokenVerification' => $token,
        ]);
        if ($user == null){
            return false;
        }
        $content = $this->tokenGenerator->decode($token);
        $expiration = $content['payload']['exp'];
        $now = (new DateTimeImmutable())->getTimestamp();
        if ($now > $expiration){
            return  false;
        }
        return true;
    }

    public function updateAfterVerified(User $user): void
    {
        $user->setEmailIsVerified(true)
            ->setEmailTokenVerification(null);
        $this->entityManager->flush();
    }
}