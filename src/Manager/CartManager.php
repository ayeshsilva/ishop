<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductQuantity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartManager
{



    public function __construct(private  RequestStack $request, private  EntityManagerInterface $em)
    {

    }

    public function addCart(Product $product, $quantity = 1)
    {
        $session = $this->request->getSession();
        $cart = $session->get('cart', []);
        $cart [] = [
            'product' => $product,
            'quantity' => $quantity
        ];

        $session->set('cart', $cart);
    }

    public function getCart()
    {
        $session = $this->request->getSession();
        $carts =  $session->get('cart');

        return $carts;
    }

    public function calculateTotal()
    {
        $carts = $this->getCart();

        $total = 0;
        foreach ($carts as $cart)
        {
            $total += $cart['quantity'] * $cart["product"]->getPrice();
        }
        return $total;
    }

    public function createOrder($customer)
    {
        $order = new Order();
        $order->setUser($customer);
        $order->setStatus('preparing');

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