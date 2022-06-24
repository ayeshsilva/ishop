<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Manager\InvoiceManager;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer/dashboard')]
class CustomerDashboardController extends AbstractController
{
    #[Route('/', name: 'app_customer_dashboard')]
    public function index(): Response
    {
        return $this->render('front/customer_dashboard/index.html.twig', [

        ]);
    }

    #[Route('/shipping-return', name: 'app_customer_dashboard_shipping_return')]
    public function shippingReturn(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findByEmailCustomerOrder($user);


        return $this->render('admin/dashboard/shipping_return.html.twig', [
            'orders' => $orders
        ]);
    }



    #[Route('/tracking/{id<\d+>}', name: 'app_customer_dashboard_tracking')]
    public function tracking(Order $order,  InvoiceManager $invoiceManager): Response
    {
        $user = $this->getUser();
        $invoiceCalculs = $invoiceManager->invoiceCalculView($order);


        $positionCurrent = array_keys( $order->step(), $order->getStatus());


        return $this->render('admin/dashboard/tacking.html.twig', [
            'order' => $order,
            'invoice_calculs' => $invoiceCalculs,
            'position_current' => $positionCurrent
        ]);
    }

    #[Route('/bill', name: 'app_customer_dashboard_bill')]
    public function bill(): Response
    {
        return $this->render('admin/dashboard/bill.html.twig', [

        ]);
    }

    #[Route('/message', name: 'app_customer_dashboard_message')]
    public function message(): Response
    {
        return $this->render('admin/dashboard/message.html.twig', [

        ]);
    }
}
