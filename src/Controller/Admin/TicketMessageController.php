<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Repository\MessageRepository;
use App\Repository\TicketRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/ticket')]
class TicketMessageController extends AbstractController
{
    #[Route('/', name: 'app_admin_ticket_message_index')]
    public function index(Request $request, TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findBy([], ['id' => 'desc']);

        return $this->render('admin/ticket_message/index.html.twig', [
           'tickets' => $tickets
        ]);
    }


    #[Route('/{id<\d+>}/message', name: 'app_admin_ticket_message_list_message')]
    public function listMessage(Request $request, Ticket $ticket, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->getTicketByUser($ticket,$ticket->getUser());

        return $this->render('admin/ticket_message/list-message.html.twig', [
            'messages' => $messages
        ]);
    }
}
