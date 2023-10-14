<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class SearchController extends AbstractController
{
    #[Route('/search', name: 'search.store', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        dd($request->toArray());
        $price = $request->get('price');
        return $this->json([
            'price'=> $price,
        ]);
    }
}
