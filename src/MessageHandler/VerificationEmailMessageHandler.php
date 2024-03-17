<?php

namespace App\MessageHandler;

use App\Message\VerificationEmailMessage;
use App\Repository\UserRepository;
use App\Services\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
class VerificationEmailMessageHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly MailService $mailService,
    )
    {
    }

    public function __invoke(
        VerificationEmailMessage $emailMessage
    ): void
    {
        $user = $this->userRepository->find($emailMessage->getUserId());
        $email = new TemplatedEmail();
        $email->to($user->getEmail())
            ->subject("Vérification de votre adresse mail")
            ->htmlTemplate('email/verification.html.twig')
            ->context([
                'link' => $this->urlGenerator->generate('email.verification', [
                    'token' => $user->getEmailTokenVerification(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

        $this->mailService->send($email);
    }
}