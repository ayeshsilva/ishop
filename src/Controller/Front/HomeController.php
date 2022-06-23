<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ProductRepository $productRepository,  PaginatorInterface $paginator): Response
    {

        $products = $paginator->paginate(
            $productRepository->findBy([], ['id' => 'desc']),
            $request->query->getInt('page', 1),
            9
        );

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
