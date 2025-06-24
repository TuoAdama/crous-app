<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\SearchService;
use App\Services\SmsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService
    )
    {
    }


    #[Route('/', name: 'app_home')]
    public function home(

    ): Response
    {
        return $this->render('pages/home/index.html.twig', [
            'config' => $this->searchService->getConfigs(),
        ]);
    }

    #[Route('/profile', name: 'app_index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $criteriaWithResults = $this->searchService->getCriteriaWithResults($user);
        return $this->render('pages/dashboard.html.twig', [
            'criteriaWithResults' => $criteriaWithResults
        ]);
    }
}
