<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Form\CartOrdersFormType;
use App\Repository\OrderItemRepository;
use App\Repository\OrdersRepository;
use App\Service\CartManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
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

            if ($form->isSubmitted() && $form->isValid()) {
                return $this->redirect($request->getUri());
            }

            return $this->render('cart/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteOrderItem(OrderItem $orderItem, Request $request, ManagerRegistry $doctrine, RequestStack $requestStack, OrdersRepository $orderRepository, CartManager $cartManager): Response
    {
        $session = $requestStack->getSession();
        $currentOrder = $orderRepository->find($session->get('order_id'));
        $entityManager = $doctrine->getManager();
        $entityManager->remove($orderItem);
        $currentOrder->setTotal($currentOrder->getTotal() - $orderItem->getTotal());
        $entityManager->flush();
        $cartManager->updateCartContent();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/product/quantity ", name="product_quantity")
     */
    public function updateProductQuantity(Request $request, OrderItemRepository $orderItemRepository, ManagerRegistry $doctrine, CartManager $cartManager): Response
    {
        $data = $request->toArray();
        $orderItem = $orderItemRepository->find($data['orderItemId']);

        $orderItem
            ->setQuantity($data['productQuantity'])
            ->setTotal($data['productQuantity'] * $orderItem->getProduct()->getPrice());

        $total = $cartManager->getOrderTotal();
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        $cartManager->updateCartContent();

        return new JsonResponse(['orderItemTotal' => $orderItem->getTotal(), 'orderTotal' => $total]);
    }

    /**
     * @Route("/clear ", name="clear")
     */
    public function clearCart(CartManager $cartManager): Response
    {
        $cartManager->clearCart();
        return $this->redirectToRoute('cart_index');
    }
}
