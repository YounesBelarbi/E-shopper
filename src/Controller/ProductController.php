<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderItemFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function productDetails(Product $product, Request $request): Response
    {

        $form = $this->createForm(OrderItemFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderItem = $form->getData();
            $orderItem->setProduct($product);

            //redirect to this route
            return $this->redirect($request->getUri());
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
