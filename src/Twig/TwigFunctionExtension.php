<?php

namespace App\Twig;


use App\Manager\CartManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class TwigFunctionExtension extends AbstractExtension
{

    public function __construct(private  CartManager $cartManager)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('caculateTotal', [$this, 'caculateTotal']),
        ];
    }

    public function caculateTotal()
    {
        return $this->cartManager->calculateTotal();
    }
}