<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductQuantity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartManager
{


    /**
     * @param RequestStack $request
     * @param EntityManagerInterface $em
     */
    public function __construct(private  RequestStack $request, private  EntityManagerInterface $em)
    {

    }

    /**
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function addCart(Product $product, int $quantity = 1): void
    {
        $session = $this->request->getSession();
        $cart = $session->get('cart', []);
        $cart [] = [
            'product' => $product,
            'quantity' => $quantity
        ];

        $session->set('cart', $cart);
    }

    /**
     * @return mixed
     */
    public function getCart(): mixed
    {
        $session = $this->request->getSession();
        $carts =  $session->get('cart');

        return $carts;
    }

    /**
     * @return float|int
     */
    public function calculateTotal(): float|int
    {
        $carts = $this->getCart();

        $total = 0;
        foreach ($carts as $cart)
        {
            $total += $cart['quantity'] * $cart["product"]->getPrice();
        }
        return $total;
    }

    /**
     * @param $customer
     * @return void
     */
    public function createOrder($customer): void
    {
        $order = new Order();
        $order->setUser($customer);
        $order->setStatus(Order::STEP1);

        $this->em->persist($order);
        $this->em->flush();

        $carts = $this->getCart();

        foreach ($carts as $cart)
        {
            $product =  $this->em->getRepository(Product::class)->find($cart["product"]);
            $productQuantity = new ProductQuantity();
            $productQuantity->setProduct($product);
            $productQuantity->setOrders($order);
            $productQuantity->setPrice($product->getPrice());
            $productQuantity->setQuantity($cart["quantity"] );
            $this->em->persist($productQuantity);
            $this->em->flush();
        }

    }
}