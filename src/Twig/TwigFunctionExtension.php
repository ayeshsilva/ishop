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
            new TwigFunction('link_to_user', [$this, 'linkToUser']),
        ];
    }

    public function caculateTotal()
    {
        return $this->cartManager->calculateTotal();
    }

    public function linkToUser($app)
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
}