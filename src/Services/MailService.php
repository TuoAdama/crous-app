<?php

namespace App\Services;

use App\Entity\SearchResult;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
        private readonly SearchService $searchService,
    )
    {
    }

    /**
     */
    function sendResultFoundNotification(SearchResult $searchResult): void
    {
        $criteria = $searchResult->getSearchCriteria();
        $user = $criteria->getUser();
        $count = count($searchResult->getResults());
        $email = new TemplatedEmail();
        $location = $criteria->getLocation();
        $email->to($user->getEmail())
            ->htmlTemplate("email/location-found.html.twig")
            ->context([
                'name' => $user->getUsername(),
                'count' => $count,
                'location' => $criteria->getLocationName(),
                'link' => $this->searchService->getLink($criteria),
            ]);
        $this->send($email);
    }

    function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
            $this->logger->info('[MAIL] Mail send to {email}', [
                'email' => $email->getTo(),
            ]);
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert('[MAIL] Envoie de mail echoué: user [email: {email}]', [
                'email' => $email->getTo(),
            ]);
        }
    }
}
