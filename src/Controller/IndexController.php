<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\SearchResultMessage;
use App\Services\SearchService;
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

    #[Route('/', name: 'app_index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $results = $this->searchService->getResults($user);
        return $this->render('pages/dashboard.html.twig', [
            'results' => $results,
        ]);
    }
}
