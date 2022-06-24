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

    public function getFunctions(): array
    {
        return [
            new TwigFunction('caculateTotal', [$this, 'caculateTotal']),
            new TwigFunction('link_to_user', [$this, 'linkToUser']),
            new TwigFunction('count_cart', [$this, 'countCart']),
        ];
    }

    public function caculateTotal(): float|int
    {
        return $this->cartManager->calculateTotal();
    }

    public function linkToUser($app): ?string
    {
        $link = null;
       if (in_array("ROLE_ADMIN", $app->getUser()->getRoles())) {
           $link = 'app_admin_dashboard';
       }
        if (in_array("ROLE_CUSTOMER", $app->getUser()->getRoles())) {
            $link = 'app_customer_dashboard';
       }
       return $link;
    }

    public function countCart()
    {
         $cart = $this->cartManager->getCart();

         return count($cart);

    }
}