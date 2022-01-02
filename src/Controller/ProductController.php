<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderItemFormType;
use App\Service\OrderManager;
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
    public function productDetails(Product $product, Request $request, OrderManager $orderManager): Response
    {
        $form = $this->createForm(OrderItemFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderManager->addItemToCurrentOrder($product, $form);

            return $this->redirect($request->getUri());
        }

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
