<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer/dashboard')]
class CustomerDashboardController extends AbstractController
{
    #[Route('/', name: 'app_customer_dashboard')]
    public function index(): Response
    {
        return $this->render('customer_dashboard/index.html.twig', [

        ]);
    }
}
