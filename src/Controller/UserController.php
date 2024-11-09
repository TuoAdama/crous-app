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
        private readonly UserService $userService,
        private readonly EmailVerificationService $emailVerificationService,
        private readonly TranslatorInterface $translator,
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

        $settingForm = $this->createForm(SettingType::class, [
            'username' => $user->getUsername(),
            'notifyByEmail' => $user->isNotifyByEmail(),
            'notifyByNumber' => $user->isNotifyByNumber(),
        ]);
        $settingForm->handleRequest($request);
        if ($settingForm->isSubmitted() && $settingForm->isValid()) {
            dd($settingForm->getData());
        }

        /** @var User $user */
        $user = $this->getUser();

        $numberForm = $this->createForm(UserNumberType::class, $user);
        $numberForm->handleRequest($request);
        if ($numberForm->isSubmitted() && $numberForm->isValid()){
            return $this->onUpdateNumber($user);
        }

        $emailForm = $this->createForm(UserEmailType::class, $user);
        $emailForm->handleRequest($request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()){
            return $this->onUpdateEmail($user);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->remove('email')
            ->remove('password')
            ->remove('submit');
        $this->onUpdated($form, $request);

        $notificationForm = $this->createForm(NotificationType::class, $user);
        $this->onUpdated($notificationForm, $request);

        return $this->render('pages/user-setting.html.twig', [
            'user' => $user,
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