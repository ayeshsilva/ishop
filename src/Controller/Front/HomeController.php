<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Entity\Product;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $products = $paginator->paginate(
            $productRepository->findBy([], ['id' => 'desc']),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('front/home/index.html.twig', compact('products'));
    }

    /**
     * @param Product $product
     * @return Response
     */
    #[Route('/shop-single/{slug}', name: 'single')]
    public function single(Product $product): Response
    {
        return $this->render('front/home/single.html.twig', compact('product'));
    }

    /**
     * @return Response
     */
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('front/home/about.html.twig',
            [
            ]);
    }

    /**
     * @param Request $request
     * @param ContactRepository $contactRepository
     * @return Response
     */
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);

            $this->addFlash('success', 'Send Messages');

            return $this->redirectToRoute('home');
        }

        return $this->render('front/home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
