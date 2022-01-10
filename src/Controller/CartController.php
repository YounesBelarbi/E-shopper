<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Form\CartOrdersFormType;
use App\Repository\OrdersRepository;
use Doctrine\Persistence\ManagerRegistry;
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

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(OrderItem $orderItem, Request $request,  ManagerRegistry $doctrine, RequestStack $requestStack, OrdersRepository $orderRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $session = $requestStack->getSession();
        $currentOrder = $orderRepository->find($session->get('order_id'));
        $entityManager->remove($orderItem);

        $orderItemTotal = $orderItem->getTotal();
        $orderTotal = $currentOrder->getTotal();

        $currentOrder
            ->setTotal($orderTotal - $orderItemTotal);

        $entityManager->flush();

        $productCart = [];
        foreach ($currentOrder->getOrderItem() as $itemOrder) {
            $productCart[$itemOrder->getProduct()->getName()] = $itemOrder->getQuantity();
        }

        $session->set('product_list', $productCart);
        return $this->redirectToRoute('cart_index');
    }
}
