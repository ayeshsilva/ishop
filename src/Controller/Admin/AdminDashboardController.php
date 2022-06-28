<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ContactRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, UserRepository $userRepository, OrderRepository $orderRepository, TicketRepository $ticketRepository, ContactRepository $contactRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();
        $customers = $userRepository->getCustomers();
        $orders = $orderRepository->findAll();
        $tickets = $ticketRepository->findAll();
        $contacts = $contactRepository->findAll();

        return $this->render('admin/dashboard/index.html.twig', compact('products', 'categories', 'customers', 'orders', 'tickets', 'contacts'));
    }


}
