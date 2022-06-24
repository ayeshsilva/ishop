<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CustomerType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_admin_customer_index', methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $users = $paginator->paginate(
            $userRepository->getCustomers(),
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(CustomerType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){



            $users = $paginator->paginate(
                $userRepository->findByCustomerEmail(
                    $search->get('words')->getData(),
                ),
                $request->query->getInt('page', 1),
                10
            );

        }

        return $this->render('admin/customer/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_admin_customer_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/customer/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/customer/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
