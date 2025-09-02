<?php

namespace App\Services;

use App\Entity\PublicContact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailService $mailService,
        private readonly TranslatorInterface $translator,
        #[Autowire("%env(CONTACT_RECEIVER)%")]
        private readonly string $contactReceiver
    )
    {
    }

    public function savePublicContact(PublicContact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
        $this->sendContactNotification($contact);
    }

    private function sendContactNotification(PublicContact $contact): void
    {
        $email = new TemplatedEmail();
        $email->to($this->contactReceiver)
            ->subject($this->translator->trans('email.contact.notification.subject'))
            ->htmlTemplate('email/contact-notification.html.twig')
            ->context([
                'contact' => $contact,
                'name' => $contact->getName(),
                'user_email' => $contact->getEmail(),
                'title' => $contact->getTitle(),
                'message' => $contact->getMessage(),
                'createdAt' => $contact->getCreatedAt(),
            ]);

        $this->mailService->send($email);
    }
}
