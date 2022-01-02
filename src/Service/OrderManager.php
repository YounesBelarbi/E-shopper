<?php

namespace App\Service;

use App\Entity\Orders;
use App\Entity\Product;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;


class OrderManager
{
    /**
     *
     * @var OrdersRepository
     */
    private $orderRepository;

    /**
     *
     * @var Session
     */
    private $session;

    /**
     *
     * @var ManagerRegistry
     */
    private $entityManager;

    function __construct(OrdersRepository $ordersRepository, RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->orderRepository = $ordersRepository;
        $this->session = $requestStack->getSession();
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @return Order
     */
    public function getCurrentOrder()
    {
        if ($this->session->has('order_id')) {
            $currentOrder = $this->orderRepository->find($this->session->get('order_id'));
        } else {
            $currentOrder = new Orders;
            $currentOrder
                ->setReference(uniqid('order_', true))
                ->setIsPaid(false)
                ->setIsShipped(false);
        }
        return $currentOrder;
    }

    /**
     * @param Product $product
     * @param [type]  $form
     * @return void
     */
    public function addItemToCurrentOrder($product, $form)
    {
        $currentOrder = $this->getCurrentOrder();
        $itemExist = $this->checkIfOrderContainsProduct($currentOrder, $product);

        if ($itemExist) {
            $itemExist->setQuantity($form->get('quantity')->getData());
            $itemExist->setTotal($product->getPrice() * $form->get('quantity')->getData());
        } else {
            $orderItem = $form->getData();
            $orderItem->setProduct($product);
            $orderItem->setTotal($product->getPrice() * $form->get('quantity')->getData());

            $currentOrder
                ->addOrderItem($orderItem);
        }

        $this->saveOrder($currentOrder);
    }

    /**
     * @param Orders $order
     * @param Product $product
     * @return void
     */
    public function checkIfOrderContainsProduct(Orders $order, Product $product)
    {
        foreach ($order->getOrderItem() as $key => $orderItem) {
            if ($orderItem->getProduct() == $product) {
                return $orderItem;
            }
        }
        return false;
    }

    /**
     * @param Orders $currentOrder
     * @return void
     */
    public function saveOrder(Orders $currentOrder)
    {
        $this->entityManager->persist($currentOrder);
        $this->getOrderTotal($currentOrder);
        $this->entityManager->flush();
        $this->saveOrderToSession($currentOrder);
    }

    /**
     * @param Orders $currentOrder
     * @return void
     */
    public function getOrderTotal(Orders $currentOrder)
    {
        $total = 0;
        foreach ($currentOrder->getOrderItem() as $itemOrder) {
            $total += $itemOrder->getTotal();
        }

        $currentOrder
            ->setTotal($total);
    }

    /**
     * @param Orders $currentOrder
     * @return void
     */
    public function saveOrderToSession(Orders $currentOrder)
    {
        $cartSession = $this->session->get('order_id', $currentOrder->getId());
        $this->session->set('order_id', $cartSession);

        $productCart = [];
        foreach ($currentOrder->getOrderItem() as $value) {
            $productCart[$value->getProduct()->getName()] = $value->getQuantity();
        }

        $this->session->set('product_list', $productCart);
    }
}
