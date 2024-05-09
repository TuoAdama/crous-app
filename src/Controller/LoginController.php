<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    #[Route('/login', name: 'app_login')]
    public function index(Request $request, Security $security): Response
    {
        if ($this->getUser() != null){
            return $this->redirectToRoute('app_index');
        }
        $user = null;
        $accountDisable = false;
        $form = $this->createForm(LoginType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->userService->attemptLogin($form->getData());
            if ($user == null){
                $this->addFlash('danger', $this->translator->trans('invalid.credentials'));
            }else if (!$user->isEnable()){
                $request->getSession()->set(User::TOKEN_SESSION_KEY, $user->getEmailTokenVerification());
                $accountDisable = true;
            }else {
                $security->login($user);
                return $this->redirectToRoute('app_index');
            }
        }

        return $this->render('authentication/login/index.html.twig', [
            'form' => $form,
            'user' => $user,
            'accountDisable' => $accountDisable,
        ]);
    }

    #[Route('/logout', 'app_logout')]
    function logout()
    {

    }
}
