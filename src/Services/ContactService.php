<?php

namespace App\Services;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ContactService
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security
    )
    {
    }

    public function save(Contact $contact): void {
        $user = $this->security->getUser();
        $contact->setUser($user);
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }
}