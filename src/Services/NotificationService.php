<?php

namespace App\Services;

use App\Entity\SearchResult;
use App\Entity\User;
use App\Enum\NotificationType;
use phpDocumentor\Reflection\Utils;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationService
{
    public function __construct(
        private readonly MailService $mailService,
        private readonly SmsInterface $smsService,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly SearchService $searchService,
        #[Autowire("%env(ENABLE_SMS)%")]
        private readonly bool $enableSms
    )
    {
    }

    public function notify(SearchResult $searchResult): void
    {
        $criteria = $searchResult->getSearchCriteria();
        $user = $criteria->getUser();
        if ($user->isNotifyByEmail()){
            $this->mailService->sendResultFoundNotification($searchResult);
        }
        if ($user->isNotifyByNumber() && $this->enableSms){
            $trans = $this->translator->trans('sms.location.found');

            $this->smsService->send($user->getNumber(),
                sprintf($trans,
                        $user->getUsername(),
                        count($searchResult->getResults()),
                        $criteria->getLocationName(),
                        $this->searchService->getLink($criteria),
                )
            );
        }
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
