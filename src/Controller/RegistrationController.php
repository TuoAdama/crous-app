<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\EmailVerificationService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly Security $security,
        private readonly EmailVerificationService $emailVerificationService,
    )
    {
    }

    /**
     * @throws \Exception
     */
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        if ($user){
            return $this->redirectToRoute('app_index');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->userService->save($user);
            $this->emailVerificationService->notify($user);
            $this->security->login($user);
            $this->addFlash('warning', 'Un mail de verification vous a été envoyé');
            return $this->redirectToRoute('app_index');
        }
        return $this->render('authentication/registration/index.html.twig', [
            'form' => $form
        ]);
    }
}
