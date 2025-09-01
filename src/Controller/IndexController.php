<?php

namespace App\Controller;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\User;
use App\Form\PublicContactType;
use App\Services\SearchService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService,
        #[Autowire("%env(SUPPORT_MAIL)%")]
        private readonly string $noreplyEmail
    )
    {
    }


    #[Route('/', name: 'app_home')]
    public function home(#[MapQueryString] ?SearchRequestQuery $query, Request $request): Response
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

        // Créer le formulaire de contact public
        $contactForm = $this->createForm(PublicContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $formData = $contactForm->getData();
            
            // Ici vous pouvez ajouter la logique pour traiter le message
            // Par exemple, envoyer un email ou sauvegarder en base
            
            $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialiser le formulaire
            $contactForm = $this->createForm(PublicContactType::class);
        }

        return $this->render('pages/home/index.html.twig', [
            'config' => $this->searchService->getConfigs(),
            'data' => $results,
            'params' => $params,
            'contactForm' => $contactForm,
            'noreplyEmail' => $this->noreplyEmail
        ]);
    }

    #[Route('/profile', name: 'app_index')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $criteriaWithResults = $this->searchService->getCriteriaWithResults($user);

        $pagination = $paginator->paginate(
            $criteriaWithResults,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/dashboard.html.twig', [
            'criteriaWithResults' => $pagination
        ]);
    }
}
