<?php

namespace App\Services;

use App\Entity\SearchResult;
use App\Entity\User;
use App\Enum\NotificationType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class NotificationService
{
    public function __construct(
        private readonly MailService $mailService,
        private readonly SmsInterface $smsService,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function notify(SearchResult $searchResult)
    {
        $criteria = $searchResult->getSearchCriteria();
        //$user = $criteria->getUser();
        //TODO Verifier les options choisies par les utilisateurs: si sms -> envoi sms, si mail -> envoi mail
        $this->mailService->sendResultFoundNotification($searchResult);
        //$this->smsService->send()
    }


    public function getToggleLink(User $user, NotificationType $notificationType): string
    {
        return match ($notificationType){
            NotificationType::NUMBER => $this->toggleNumberLink($user),
            NotificationType::EMAIL => $this->toggleEmailLink($user),
        };
    }

    public function toggleNumberLink(User $user): string
    {
        return $this->urlGenerator->generate('notification.toggle.number', [
            'id'=> $user->getId(),
            'token' => $this->getToken($user),
            'value' => !$user->isNotifyByNumber(),
        ]);
    }

    public function toggleEmailLink(User $user): string
    {
        return $this->urlGenerator->generate('notification.toggle.email', [
            'id'=> $user->getId(),
            'token' => $this->getToken($user),
            'value' => !$user->isNotifyByEmail(),
        ]);
    }


    public function getToken(User $user): string
    {
        return $this->tokenManager->getToken('notification'.$user->getEmail())->getValue();
    }
}