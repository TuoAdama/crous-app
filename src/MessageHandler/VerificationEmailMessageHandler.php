<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Enum\EmailVerificationType;
use App\Message\VerificationEmailMessage;
use App\Repository\UserRepository;
use App\Services\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsMessageHandler]
class VerificationEmailMessageHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly MailService $mailService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function __invoke(
        VerificationEmailMessage $emailMessage
    ): void
    {
        $user = $this->userRepository->find($emailMessage->getUserId());
        $email = $this->getTemplate($emailMessage->getType(), $user);
        $this->mailService->send($email);
    }


    public function getTemplate(EmailVerificationType $type, User $user): TemplatedEmail
    {
        $template = new TemplatedEmail();
        $template->to($user->getEmail());
        $subject = $this->translator->trans('mail.verification.change.mail.subject');
        $route = "email.verification";
        $message = "mail.template.change.mail.body";
        $title = "mail.template.change.mail.title";

        if ($type === EmailVerificationType::VERIFICATION_AFTER_REGISTRATION){
            $subject = $this->translator->trans('mail.verification.registration.user.subject');
            $route = "app_registration.verification";
            $title = "mail.template.user.registration.title";
            $message = "mail.template.user.registration.body";
        }
        $template
            ->subject($subject)
            ->htmlTemplate("email/mail-verification.html.twig")
            ->context([
                'link' => $this->urlGenerator->generate($route, [
                    'token' => $user->getEmailTokenVerification(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'message' => $message,
                'title' => $title,
            ]);
        return $template;
    }
}