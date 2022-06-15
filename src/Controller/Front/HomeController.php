<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {


        $products = $productRepository->findAll();
        return $this->render('front/home/index.html.twig', compact('products'));
    }

    #[Route('/shop-single/{slug}', name: 'single')]
    public function single(Product $product): Response
    {

        return $this->render('front/home/single.html.twig', compact('product'));
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/home/about.html.twig', [
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('front/home/contact.html.twig', [
        ]);
    }
}
