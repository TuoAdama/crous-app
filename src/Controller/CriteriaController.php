<?php

namespace App\Controller;

use App\Entity\SearchCriteria;
use App\Form\SearchCriteriaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CriteriaController extends AbstractController
{
    #[Route('/criteria', name: 'app_criteria')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        $searchCriteria = new SearchCriteria();
        $form = $this->createForm(SearchCriteriaType::class, $searchCriteria);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            dd($form);
        }
        return $this->render('pages/criteria/criteria.html.twig',  [
            'identifier' => $this->getUser()->getUserIdentifier(),
            'form' => $form,
        ]);
    }
}
