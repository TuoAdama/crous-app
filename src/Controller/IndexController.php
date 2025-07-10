<?php

namespace App\Controller;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\User;
use App\Services\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
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
    public function home(#[MapQueryString] ?SearchRequestQuery $query): Response
    {
        $params = []; $results = [];

        if ($query){
            if ($query->type) {
                $params['type'] = $query->type;
            }
            if ($query->minArea) {
                $params['minArea'] = $query->minArea;
            }
            if ($query->minPrice) {
                $params['minPrice'] = $query->minPrice;
            }
            if ($query->name) {
                $params['name'] = $query->name;
            }
            if ($query->extent) {
                $params['extent'] = $query->extent;
                $results = $this->searchService->getLocationByQuery($query);
            }
        }

        return $this->render('pages/home/index.html.twig', [
            'config' => $this->searchService->getConfigs(),
            'data' => $results,
            'params' => $params
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
