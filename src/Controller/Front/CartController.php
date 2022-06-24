<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\Product;
use App\Manager\CartManager;
use App\Manager\InvoiceManager;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\StripeService;
use JetBrains\PhpStorm\NoReturn;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 *
 */
#[Route('/cart')]
class CartController extends AbstractController
{

    /**
     * @param CartManager $cartManager
     * @return Response
     */
    #[NoReturn] #[Route('/', name: 'app_front_cart')]
    public function index( CartManager $cartManager): Response
    {
        $carts =  $cartManager->getCart();



        return $this->render('front/cart/index.html.twig', ['carts' => $carts]);
    }


    /**
     * @param Product $product
     * @param CartManager $cartManager
     * @return Response
     */
    #[Route('/add/{id}', name: 'app_front_cart_add')]
    public function add(Product $product, CartManager $cartManager): Response
    {
        $cartManager->addCart($product);

        return $this->redirectToRoute('app_front_cart');
    }

    /**
     * @throws \Stripe\Exception\ApiErrorException
     */
    #[Route('/pay', name: 'app_front_cart_pay')]
    public function pay(StripeService $stripeManager, CartManager $cartManager): JsonResponse
    {
        $carts = $cartManager->getCart();

        if (empty($carts)) {
            return $this->redirectToRoute('home');
        }

        $priceTotal = $cartManager->calculateTotal();
        $data = ['clientSecret' => $stripeManager->createPayment($priceTotal)];

        return new JsonResponse($data );
    }


    /**
     * @param Request $request
     * @param StripeService $stripeService
     * @param CartManager $cartManager
     * @return Response
     */
    #[Route('/success', name: 'app_front_cart_success')]
    public function success(Request $request,StripeService $stripeService, CartManager $cartManager): Response
    {

        $session = $request->getSession( );
        $cartManager->createOrder($this->getUser());
        
        $session->remove('cart');

        return $this->render('front/cart/success.html.twig', [

        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route('/delete/{id}', name: 'app_front_cart_remove')]
    public function delete($id, Request $request)
    {
        $session = $request->getSession();
        $carts = $session->get('cart');
        unset($carts[$id]);

        $session->set('cart', $carts);

        return $this->redirectToRoute('app_front_cart');

    }

    /**
     * @param CartManager $cartManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    #[Route('/checkout', name: 'front_checkout')]
    public function checkout(CartManager $cartManager)
    {

        $carts = $cartManager->getCart();

        if (empty($carts)) {
            return $this->redirectToRoute('home');
        }

        return $this->render('front/cart/checkout.html.twig', [

            'stripe_api_key' => $_ENV["STRIPE_API_KEY"]
        ]);
    }

    /**
     * @param Pdf $knpSnappyPdf
     * @param Order $order
     * @param InvoiceManager $invoiceManager
     * @return Response
     */
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

    /**
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    #[Route('/ajax/add-cart',name:'app_cart_ajax_add_cart', methods: ['GET','POST'])]
    public function ajaxAddCart(Request $request, ProductRepository $productRepository, CartManager $cartManager) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!is_null($data)) {

            $product = $productRepository->find($data['id']);
            $cartManager->addCart($product, $data['quantity'] );

            return new JsonResponse(["success" => "OK"], 200);
        }

        return new JsonResponse(["error" => ""], 400);





    }

}
