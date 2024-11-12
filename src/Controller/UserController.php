<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService              $userService,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/setting', name: 'user.setting')]
    public function setting(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('pages/user-setting.html.twig', [
            'userData' => $user->jsonSerialize(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/setting/verification/resend', name: 'user.verification.resend')]
    public function resendCode(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $this->userService->verifyNumber($user);
        return $this->redirectToRoute('user.verification.number', [
            'token' => $user->getNumberTokenVerification(),
        ]);
    }

}