<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Manager\InvoiceManager;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_admin_order_index', methods: ['GET'])]
    public function index(Request $request, OrderRepository $orderRepository, PaginatorInterface $paginator): Response
    {
        $orders = $paginator->paginate(
                $orderRepository->findBy([], ['id'=>'desc']),
                $request->query->getInt('page', 1),
                10
            );


        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @param Order $order
     * @param InvoiceManager $invoiceManager
     * @return Response
     */
    #[Route('/{id<\d+>}', name: 'app_admin_order_show', methods: ['GET'])]
    public function show(Order $order, InvoiceManager $invoiceManager): Response
    {
        $invoiceCalculs = $invoiceManager->invoiceCalculView($order);

        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
            'invoice_calculs' => $invoiceCalculs
        ]);
    }




    #[Route('/_ajax/data', name: 'admin_order_ajax', methods: ['POST'])]
    public function changeStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $id = $request->request->get('id');
        $status = $request->request->get('status');

        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->persist($order);
        $em->flush();

        return new JsonResponse(["ok"]);
    }


}
