<?php

namespace App\Controller;

use App\Repository\SearchCriteriaRepository;
use App\Services\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CriteriaController extends AbstractController
{
    #[Route('/criteria', name: 'app_criteria')]
    public function index(): Response
    {
        return $this->render('pages/criteria/criteria.html.twig');
    }


    #[Route('/search', name: "search")]
    public function search(SearchCriteriaRepository $criteriaRepository, SearchService $searchService)
    {
        $criteria = $criteriaRepository->find(1);
        $requestBody = $searchService->getRequestBody($criteria);
        $searchService->search($requestBody);
    }
}
