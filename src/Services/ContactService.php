<?php

namespace App\Services;

use App\Entity\PublicContact;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailService $mailService
    )
    {
    }

    public function savePublicContact(PublicContact $contact): void
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }
}
