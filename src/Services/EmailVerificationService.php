<?php

namespace App\Services;

use App\Entity\User;
use App\Message\VerificationEmailMessage;
use App\Repository\UserRepository;
use App\Services\Token\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailVerificationService
{

    public function __construct(
        private readonly TokenGenerator $tokenGenerator,
        private readonly ExpirationService $expirationService,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus,
    )
    {

    }

    /**
     * @throws Exception
     */
    public function notify(User $user): void
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
        $this->bus->dispatch(new VerificationEmailMessage($user->getId()));
    }
}