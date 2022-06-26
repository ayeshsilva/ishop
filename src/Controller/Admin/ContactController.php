<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\SearchFormType;
use App\Repository\ContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_admin_contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ContactRepository $contactRepository, PaginatorInterface $paginator): Response
    {
        $contacts = $paginator->paginate(
            $contactRepository->findBy([], ['id' => 'desc']),
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchFormType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $contacts = $paginator->paginate(
                $contactRepository->search(
                    $search->get('words')->getData(),
                ),
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contacts,
            'form' => $form->createView()
        ]);
    }



    #[Route('/{id<\d+>}', name: 'app_admin_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('admin/contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }


}
