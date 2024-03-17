<?php

namespace App\Controller;

use App\Services\EmailVerificationService;
use App\Services\Token\JWTServiceToken;
use App\Services\Token\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class EmailVerificationController extends AbstractController
{
    public function __construct(
        private readonly EmailVerificationService $emailVerificationService,
    )
    {
    }

    #[Route('/email/verification/{token}', name: 'email.verification')]
    public function verificationEmail(Request $request, string $token): Response
    {
        $isValid = $this->emailVerificationService->tokenIsValid($token);
        dd($isValid);
    }
}