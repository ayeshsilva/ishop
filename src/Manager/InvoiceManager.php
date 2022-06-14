<?php

namespace App\Manager;

use App\Repository\OrderRepository;

class InvoiceManager
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function invoiceCalculView($order)
    {
        $listUserOrders = $this->orderRepository->findbyUserOrder($order);

        $arrayOrderProductQuantities = [];
        $totalAmount = 0;
        foreach ($listUserOrders as $listUserOrder) {
            $arrayOrderProductQuantities = [];
            foreach ($listUserOrder->getProductQuantities() as $productQuantity) {

                $pQArray [] = [
                    "product" => $productQuantity->getProduct()->getName(),
                    "description" => $productQuantity->getProduct()->getDescription(),
                    "quantity" => $productQuantity->getQuantity(),
                    "price" => $productQuantity->getPrice(),
                    "subtotal" => $productQuantity->getPrice() * $productQuantity->getQuantity(),
                ];
                $arrayOrderProductQuantities['item'] = $pQArray;
                $totalAmount += $productQuantity->getPrice() * $productQuantity->getQuantity();
                $arrayOrderProductQuantities ['total_amount'] =
                    $totalAmount;
            }

        }

        return $arrayOrderProductQuantities;
    }

}