<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/details/{id}", name="details")
     */
    public function productDetails(Product $product): Response
    {
        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
}
