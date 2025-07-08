<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertController extends AbstractController
{


    #[Route('/alert', name: 'app_alert_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('alert/create.html.twig');
    }
}
