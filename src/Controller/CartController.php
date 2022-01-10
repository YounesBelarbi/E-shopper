<?php

namespace App\Controller;

use App\Form\CartOrdersFormType;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, RequestStack $requestStack, OrdersRepository $ordersRepository): Response
    {

        $session = $requestStack->getSession();

        if ($session->has('order_id')) {
            $order = $ordersRepository->find($session->get('order_id'));
            $form = $this->createForm(CartOrdersFormType::class, $order);
            $form->handleRequest($request);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirect($request->getUri());
        }

        return $this->render('cart/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
