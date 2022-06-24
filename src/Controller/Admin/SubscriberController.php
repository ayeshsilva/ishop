<?php

namespace App\Controller\Admin;

use App\Entity\Subscriber;
use App\Form\SubscriberType;
use App\Repository\SubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscriber')]
class SubscriberController extends AbstractController
{
    #[Route('/', name: 'app_admin_subscriber_index', methods: ['GET'])]
    public function index(SubscriberRepository $subscriberRepository): Response
    {
        return $this->render('admin/subscriber/index.html.twig', [
            'subscribers' => $subscriberRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_subscriber_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SubscriberRepository $subscriberRepository): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriberRepository->add($subscriber, true);

            return $this->redirectToRoute('app_admin_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/subscriber/new.html.twig', [
            'subscriber' => $subscriber,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_subscriber_show', methods: ['GET'])]
    public function show(Subscriber $subscriber): Response
    {
        return $this->render('admin/subscriber/show.html.twig', [
            'subscriber' => $subscriber,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_subscriber_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subscriber $subscriber, SubscriberRepository $subscriberRepository): Response
    {
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriberRepository->add($subscriber, true);

            return $this->redirectToRoute('app_admin_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/subscriber/edit.html.twig', [
            'subscriber' => $subscriber,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_subscriber_delete', methods: ['POST'])]
    public function delete(Request $request, Subscriber $subscriber, SubscriberRepository $subscriberRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subscriber->getId(), $request->request->get('_token'))) {
            $subscriberRepository->remove($subscriber, true);
        }

        return $this->redirectToRoute('app_admin_subscriber_index', [], Response::HTTP_SEE_OTHER);
    }
}
