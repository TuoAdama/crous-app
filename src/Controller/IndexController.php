<?php

namespace App\Controller;

use App\DTO\Request\SearchRequestQuery;
use App\Entity\PublicContact;
use App\Entity\User;
use App\Form\PublicContactType;
use App\Services\ContactService;
use App\Services\SearchService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly TranslatorInterface $translator,
        private readonly ContactService $contactService,
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

        $publicContact = new PublicContact();
        $contactForm = $this->createForm(PublicContactType::class, $publicContact);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $this->addFlash('success', $this->translator->trans("public.contact.success.message"));
            $this->contactService->savePublicContact($publicContact);
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
