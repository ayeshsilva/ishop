<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{



    public function __construct(private  RequestStack $requestStack)
    {

    }


}