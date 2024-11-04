<?php

namespace App\Controller;

use App\Entity\SearchCriteria;
use App\Entity\User;
use App\Form\NotificationType;
use App\Form\SearchCriteriaType;
use App\Services\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class CriteriaController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
        private readonly SearchService $searchService,
    )
    {
    }

    #[Route('/criteria', name: 'app_criteria')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        $searchCriteria = new SearchCriteria();
        $form = $this->createForm(SearchCriteriaType::class, $searchCriteria);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var User $user */
            $user = $this->getUser();
            $this->addFlash('success', $this->translator->trans('flash.messages.update'));
            $this->searchService->save($searchCriteria,  $user, $request);
        }
        $notificationForm = $this->createForm(NotificationType::class, $this->getUser());
        $notificationForm->handleRequest($request);
        if ($notificationForm->isSubmitted() && $notificationForm->isValid()){
            $this->entityManager->flush();
            $this->addFlash('success', $this->translator->trans('flash.messages.update'));
            return $this->redirectToRoute('app_index');
        }
        return $this->render('pages/criteria/criteria.html.twig',  [
            'identifier' => $this->getUser()->getUserIdentifier(),
            'form' => $form,
            'notificationForm' => $notificationForm,
        ]);
    }

    #[Route('/criteria/delete', name: 'app_criteria_delete', methods: ['POST'])]
    public function delete(): JsonResponse
    {
        return $this->json([
            'message' => 'success',
        ], Response::HTTP_OK);
    }

    public function edit(Request $request, int $criteriaId): Response
    {
        dd($request, $criteriaId);
    }
}
