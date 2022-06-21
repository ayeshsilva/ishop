<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\Product;
use App\Manager\CartManager;
use App\Manager\InvoiceManager;
use App\Service\StripeService;
use JetBrains\PhpStorm\NoReturn;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/cart')]
class CartController extends AbstractController
{

    #[NoReturn] #[Route('/', name: 'app_front_cart')]
    public function index(Request $request, StripeService $stripeManager): Response
    {
        $session = $request->getSession();
        $baskets = $session->get('basket');

        return $this->render('front/cart/index.html.twig', ['baskets' => $baskets]);
    }


    #[Route('/add/{id}', name: 'app_front_cart_add')]
    public function add(Product $product, Request $request, Security $security): Response
    {

        $session = $request->getSession();

        $basket = $session->get('basket', []);
        $basket [] = [
            'product' => $product,
            'quantity' => 1
        ];

        $session->set('basket', $basket);

        return $this->redirectToRoute('app_front_cart');
    }

    #[Route('/pay', name: 'app_front_cart_pay')]
    public function pay(StripeService $stripeManager, CartManager $cartManager): JsonResponse
    {
        $baskets = $cartManager->getBasket();

        if (empty($baskets)) {
            return $this->redirectToRoute('home');
        }

        $priceTotal = $cartManager->calculateTotal();
        $data = ['clientSecret' => $stripeManager->createPayment($priceTotal)];

        return new JsonResponse($data );
    }


    #[Route('/success', name: 'app_front_cart_success')]
    public function success(Request $request,StripeService $stripeService, CartManager $cartManager): Response
    {

        $session = $request->getSession( );

        $cartManager->createOrder($this->getUser());

        //$mailer->sendSuccessCart($this->getUser());

        $session->remove('basket');

        return $this->render('front/cart/success.html.twig', [

        ]);
    }

    #[Route('/delete/{id}', name: 'app_front_cart_remove')]
    public function delete($id, Request $request)
    {
        $session = $request->getSession();
        $baskets = $session->get('basket');
        unset($baskets[$id]);

        $session->set('basket', $baskets);

        return $this->redirectToRoute('app_front_cart');

    }

    #[Route('/checkout', name: 'front_checkout')]
    public function checkout(CartManager $cartManager)
    {

        $baskets = $cartManager->getBasket();

        if (empty($baskets)) {
            return $this->redirectToRoute('home');
        }

        return $this->render('front/cart/checkout.html.twig', [

            'stripe_api_key' => $_ENV["STRIPE_API_KEY"]
        ]);
    }

    #[Route('/invoice/{id<\d+>}', name: 'app_cart_invoice', methods: ['GET'])]
    public function invoice(Pdf $knpSnappyPdf, Order $order,  InvoiceManager $invoiceManager): Response
    {
        $invoiceCalculs = $invoiceManager->invoiceCalculView($order);
        $html = $this->renderView('admin/order/invoice.html.twig', [
            'order' => $order,
            'invoice_calculs' => $invoiceCalculs
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'invoice.pdf'
        );
    }

}
