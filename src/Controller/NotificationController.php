<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route("/notification")]
class NotificationController extends AbstractController
{
    public function __construct(
        private readonly NotificationService $notificationService,
    )
    {
    }

    #[Route('/enable/{token}/number/value/{value}', name: 'notification.toggle.number')]
    public function toggleNumberNotification(Request $request, string $token, bool $value): Response
    {

    }

    #[Route('/enable/{token}/email/user/{id}/value/{value}', name: 'notification.toggle.email')]
    public function toggleEmailNotification(Request $request, User $user, string $token, bool $value): Response
    {
        dd($token, $value, $user);
    }
}
