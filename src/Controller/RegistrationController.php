<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\EmailVerificationType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\EmailVerificationService;
use App\Services\Token\TokenGenerator;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly Security $security,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly TranslatorInterface $translator,
        private readonly TokenGenerator $tokenGenerator,
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $entityManager,
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
            $this->emailVerificationService->notify($user, EmailVerificationType::VERIFICATION_AFTER_REGISTRATION);
            $this->security->login($user);
            $request->getSession()->set(User::TOKEN_SESSION_KEY, $user->getEmailTokenVerification());
            return $this->render('pages/registration/after-registration.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->render('authentication/registration/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/registration/verification/{token}', name: 'app_registration.verification')]
    public function verification(Request $request, string $token): Response
    {
        if (!$this->emailVerificationService->tokenIsValid($token)){
            throw $this->createNotFoundException($this->translator->trans('page.notfound'));
        }
        $request->getSession()->remove(User::TOKEN_SESSION_KEY);
        $userId = $this->tokenGenerator->decode($token)['payload']['sub'];
        $user = $this->repository->find($userId);
        $user->setEmailTokenVerification(null);
        $user->setEnable(true);
        $this->entityManager->flush();
        $this->security->login($user);
        return $this->redirectToRoute('app_index');
    }

    /**
     * @throws Exception
     */
    #[Route('/registration/resend/verification/mail/{id}', name: 'app_registration.resend.mail')]
    public function resendMail(Request $request, User $user): Response
    {
        $token = $request->getSession()->get(User::TOKEN_SESSION_KEY);
        if ($user->getEmailTokenVerification() !== $token){
            throw $this->createNotFoundException();
        }
        $this->addFlash('warning', $this->translator->trans('flash.messages.mail.resend'));
        $this->emailVerificationService->notify($user, EmailVerificationType::VERIFICATION_AFTER_REGISTRATION);
        $request->getSession()->set(User::TOKEN_SESSION_KEY, $user->getEmailTokenVerification());
        return $this->render('pages/registration/after-registration.html.twig', [
            'user' => $user,
        ]);
    }
}
