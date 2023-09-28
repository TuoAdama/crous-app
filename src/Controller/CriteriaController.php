<?php

namespace App\Controller;

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
}
