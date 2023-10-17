<?php

namespace App\Controller\API;

use App\Entity\SearchCriteria;
use App\Repository\SearchCriteriaRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api')]
class SearchController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SearchCriteriaRepository $criteriaRepository
    )
    {
    }

    #[Route('/search', name: 'search.store', methods: ['POST'])]
    public function index(
        Request $request
    ): JsonResponse
    {

        $content = $request->toArray();
        $searchCriteria = new SearchCriteria();
        $searchCriteria->setLocation($content['location'] ?? []);
        $searchCriteria->setType($content['type'] ?? []);
        if (is_numeric($content['price'])){
            $searchCriteria->setPrice($content['price']);
        }
        $constraints = $this->validator->validate($searchCriteria);
        if ($constraints->count()){
            $errors = [];
            foreach ($constraints as $constraint) {
                $errors[$constraint->getPropertyPath()] = $constraint->getMessage();
            }
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $searchCriteria->setCreatedAt(new DateTimeImmutable());
        $this->criteriaRepository->save($searchCriteria);
        return $this->json($request->getContent(), 200);
    }
}
