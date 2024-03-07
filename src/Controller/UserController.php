<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserNumberType;
use App\Services\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/setting', name: 'user.setting')]
    public function setting(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $numberForm = $this->createForm(UserNumberType::class, $user);
        $numberForm->handleRequest($request);
        if ($numberForm->isSubmitted() && $numberForm->isValid()){
            $this->userService->verifyNumber($user);
        }
        return $this->render('pages/user-setting.html.twig', [
            'user' => $user,
            'numberForm' => $numberForm
        ]);
    }


    public function tokenIsValid(Request $request): bool
    {
        return $this->isCsrfTokenValid('user_update_item', $request->get('_token'));
    }
}