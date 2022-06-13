<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_admin_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('admin/order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }


    #[Route('/invoice', name: 'admin_order_invoice', methods: ['GET'])]
    public function invoice()
    {

        return $this->render('admin/order/invoice.html.twig', [

        ]);

    }

    #[Route('/{id}', name: 'app_admin_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/_ajax/data', name: 'admin_order_ajax', methods: ['POST'])]
    public function changeStatus(Request $request, EntityManagerInterface $em) : JsonResponse
    {
        $id =  $request->request->get('id');
        $status =  $request->request->get('status');


        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->persist($order);
        $em->flush();

        return new JsonResponse(["ok"]);
    }





}
