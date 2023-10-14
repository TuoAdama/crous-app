<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CriteriaController extends AbstractController
{
    #[Route('/criteria', name: 'app_criteria')]
    public function index(): Response
    {
        return $this->render('pages/criteria/criteria.html.twig');
    }

    #[Route('/criteria/location', name: 'criteria.location', methods: 'POST')]
    public function location(Request $request): JsonResponse
    {
        return new JsonResponse(compact($location, $price),);
    }
}
