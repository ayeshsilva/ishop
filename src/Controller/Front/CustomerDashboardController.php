<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\Ticket;
use App\Form\MessageType;
use App\Form\TicketType;
use App\Manager\InvoiceManager;
use App\Repository\MessageRepository;
use App\Repository\OrderRepository;
use App\Repository\TicketRepository;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/ticket', name: 'app_customer_dashboard_ticket')]
    public function ticket(Request $request, TicketRepository $ticketRepository): Response
    {
       $user = $this->getUser();
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $ticket->setUser($user);
            $ticketRepository->add($ticket, true);
            $this->addFlash('success', 'Ticket has been created');
            return $this->redirectToRoute('app_customer_dashboard_ticket');
        }

        return $this->render('admin/dashboard/ticket.html.twig', [
            'form' => $form->createView(),
            'tickets' => $ticketRepository->findBy(['user' => $user], ['id' => 'desc'])


        ]);
    }

    #[Route('/message/{id}', name: 'app_customer_dashboard_message')]
    public function message(Request $request, Ticket $ticket, MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setCustomer($user);
            $message->setTicket($ticket);
            $messageRepository->add($message, true);
            $this->addFlash('success', 'Message has been created');
            return $this->redirectToRoute('app_customer_dashboard_message', [
                'id' => $ticket->getId()
            ]);
        }

         $message = $messageRepository->getTicketByUser($ticket,$user);
        return $this->render('admin/dashboard/message.html.twig', [
            'form' => $form->createView(),
            'messages' => $message


        ]);
    }
}
