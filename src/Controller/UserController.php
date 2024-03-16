<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserNumberType;
use App\Form\VerificationNumberType;
use App\Services\Token\SmsTokenValidator;
use App\Services\UserService;
use DateTime;
use DateTimeImmutable;
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
        private readonly SmsTokenValidator $smsTokenValidator
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
            $token = $this->userService->updateToken($user);
            $this->userService->flush();
            return $this->redirectToRoute('user.verification.number', [
                'token' =>  $token
            ]);
        }
        return $this->render('pages/user-setting.html.twig', [
            'user' => $user,
            'numberForm' => $numberForm
        ]);
    }


    #[Route('/setting/verification/number/{token}', name: 'user.verification.number')]
    public function verification(Request $request, string $token): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $isValid = $this->smsTokenValidator->isValid($user, $token);
        if (!$isValid){
           throw $this->createNotFoundException();
        }
        $form = $this->createForm(VerificationNumberType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->getUser();
            $isValid = $this->userService->codeIsValid($user, $form->getData()['code']);
            if (!$isValid){
                $this->addFlash('danger', 'Code incorrect');
                return $this->redirectToRoute('user.verification.number', [
                    'token' => $token
                ]);
            }
            $this->addFlash('success', 'Numéro mis à jour');
            return $this->redirectToRoute('user.setting');
        }
        $expiredAt = $user->getTemporaryCodeExpiredAt();
        $now = new DateTimeImmutable();
        $seconds = 0;
        if ($expiredAt > $now){
            $seconds = $expiredAt->getTimestamp() - $now->getTimestamp();
        }
        return $this->render('pages/number-verification.html.twig',[
            'form' => $form,
            'seconds' => $seconds,
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