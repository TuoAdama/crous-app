<?php

namespace App\Services;

use App\Entity\SearchResult;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private string $author;
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly LoggerInterface $logger,
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
        $email = new Email();
        $email->to($user->getEmail())
            ->html("<h1>Bonjour vous avez ".$count." logement(s) trouvé(s)</h1>");
        $this->send($email);
    }

    function send(Email $email): void
    {
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert('envoie de mail echoué: user [email: {email}]', [
                'email' => $email->getFrom()[0]->getAddress()
            ]);
        }
    }
}