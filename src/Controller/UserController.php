<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NotificationType;
use App\Form\SettingType;
use App\Form\UserEmailType;
use App\Form\UserNumberType;
use App\Form\UserType;
use App\Form\VerificationNumberType;
use App\Services\EmailVerificationService;
use App\Services\Token\SmsTokenValidator;
use App\Services\UserService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService              $userService,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly TranslatorInterface      $translator, private readonly EntityManagerInterface $entityManager,
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
        $settingForm = $this->createForm(SettingType::class, $user);
        $settingForm->handleRequest($request);

        if ($settingForm->isSubmitted() && $settingForm->isValid()) {
            $this->entityManager->flush();
        }
        return $this->render('pages/user-setting.html.twig', [
            'settingForm' => $settingForm,
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

    /**
     * @throws Exception
     */
    public function onUpdateEmail(User $user): Response
    {
        $this->emailVerificationService->notify($user);
        $this->addFlash('warning', 'Un mail de confirmation vous a été envoyé');
        return $this->redirectToRoute('user.setting');
    }

    /**
     * @throws Exception
     */
    public function onUpdateNumber(User $user): Response
    {
        $this->userService->verifyNumber($user);
        $token = $this->userService->updateToken($user);
        $this->userService->flush();
        return $this->redirectToRoute('user.verification.number', [
            'token' =>  $token
        ]);
    }



    public function onUpdated(FormInterface $form, Request $request): void
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->userService->flush();
            $this->addFlash('success', $this->translator->trans("flash.messages.update"));
        }
    }

}