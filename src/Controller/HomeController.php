<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        //get page from url parameter
        $currentPage = (int)$request->query->get('page', 1);

        //number of products displayed per page
        $productPerPage = 9;

        //calculate of the number of pages 
        $numberTotalOfProducts = $productRepository->getNumberOfProduct();
        $numberOfPages = ceil((int)$numberTotalOfProducts / $productPerPage);
        
        $products = $productRepository->getProducts($currentPage, $productPerPage);
        return $this->render('home/index.html.twig', compact('products', 'numberOfPages', 'currentPage'));
    }
}
