<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\ContactService;
use PhpParser\Node\Expr\AssignOp\Concat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{

    public function __construct(
        private readonly ContactService $contactService,
        private readonly TranslatorInterface  $translator
    )
    {
    }

    #[Route('/contact', name: 'app_contact')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $emptyForm = clone $form;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->contactService->save($contact);
            $this->addFlash('success', $this->translator->trans('contact.success.message'));
            $form = $emptyForm;
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
