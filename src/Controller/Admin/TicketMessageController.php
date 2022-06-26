<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Entity\Ticket;
use App\Form\MessageType;
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
        $user = $this->getUser();
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $message->setTeam($user);
            $message->setTicket($ticket);
            $messageRepository->add($message, true);

            $this->addFlash('success', 'create Message');

            return  $this->redirectToRoute('app_admin_ticket_message_list_message', ['id' => $ticket->getId()]);
        }

        $messages = $messageRepository->getTicketByUser($ticket,$ticket->getUser());

        return $this->render('admin/ticket_message/list-message.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }
}
