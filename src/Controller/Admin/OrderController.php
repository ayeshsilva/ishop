<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Form\SearchFormType;
use App\Manager\InvoiceManager;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order')]
class OrderController extends AbstractController
{
    /**
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'app_admin_order_index', methods: ['GET','POST'])]
    public function index(Request $request, OrderRepository $orderRepository, PaginatorInterface $paginator): Response
    {
        $orders = $paginator->paginate(
            $orderRepository->findBy([], ['id' => 'desc']),
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SearchFormType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $orders = $paginator->paginate(
                $orderRepository->search(
                    $search->get('words')->getData(),
                ),
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Order $order
     * @param InvoiceManager $invoiceManager
     * @return Response
     */
    #[Route('/{id<\d+>}', name: 'app_admin_order_show', methods: ['GET'])]
    public function show(Request $request, Order $order, InvoiceManager $invoiceManager): Response
    {
        $invoiceCalculs = $invoiceManager->invoiceCalculView($order);

        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
            'invoice_calculs' => $invoiceCalculs,

        ]);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/_ajax/data', name: 'admin_order_ajax', methods: ['POST'])]
    public function changeStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_null($data)) {

            $order = $em->getRepository(Order::class)->find($data['id']);
            $order->setStatus($data['step']);
            $em->flush();
            return new JsonResponse(["success" => "OK"], 200);
        }

        return new JsonResponse(["error" => "error"], 400);
    }
}
