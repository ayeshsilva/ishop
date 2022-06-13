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

    public function getBasket()
    {
        $session = $this->request->getSession();
        $baskets =  $session->get('basket');

        return $baskets;
    }

    public function calculateTotal()
    {
        $baskets = $this->getBasket();

        $total = 0;
        foreach ($baskets as $basket)
        {
            $total += $basket['quantity'] * $basket["product"]->getPrice();
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

        $baskets = $this->getBasket();

        foreach ($baskets as $basket)
        {
            $product =  $this->em->getRepository(Product::class)->find($basket["product"]);
            $productQuantity = new ProductQuantity();
            $productQuantity->setProduct($product);
            $productQuantity->setOrders($order);
            $productQuantity->setPrice($product->getPrice());
            $productQuantity->setQuantity($basket["quantity"] );
            $this->em->persist($productQuantity);
            $this->em->flush();
        }

    }
}