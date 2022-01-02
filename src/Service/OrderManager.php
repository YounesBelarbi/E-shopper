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

    /**
     * @var Orders
     */
    private $currentOrder;

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
            $this->currentOrder = $this->orderRepository->find($this->session->get('order_id'));
        } else {
            $this->currentOrder = new Orders;
            $this->currentOrder
                ->setReference(uniqid('order_', true))
                ->setIsPaid(false)
                ->setIsShipped(false);
        }
    }

    /**
     * @param Product $product
     * @param [type]  $form
     * @return void
     */
    public function addItemToCurrentOrder($product, $form)
    {
        $this->getCurrentOrder();
        $itemExist = $this->checkIfOrderContainsProduct($product);

        if ($itemExist) {
            $itemExist->setQuantity($form->get('quantity')->getData());
            $itemExist->setTotal($product->getPrice() * $form->get('quantity')->getData());
        } else {
            $orderItem = $form->getData();
            $orderItem->setProduct($product);
            $orderItem->setTotal($product->getPrice() * $form->get('quantity')->getData());

            $this->currentOrder
                ->addOrderItem($orderItem);
        }

        $this->saveOrder();
    }

    /**
     * @param Product $product
     * @return void
     */
    public function checkIfOrderContainsProduct(Product $product)
    {
        foreach ($this->currentOrder->getOrderItem() as $orderItem) {
            if ($orderItem->getProduct() == $product) {
                return $orderItem;
            }
        }
        return false;
    }

    /**
     * @return void
     */
    public function saveOrder()
    {
        $this->entityManager->persist($this->currentOrder);
        $this->getOrderTotal();
        $this->entityManager->flush();
        $this->saveOrderToSession();
    }

    /**
     * @return void
     */
    public function getOrderTotal()
    {
        $total = 0;
        foreach ($this->currentOrder->getOrderItem() as $itemOrder) {
            $total += $itemOrder->getTotal();
        }

        $this->currentOrder
            ->setTotal($total);
    }

    /**
     * @return void
     */
    public function saveOrderToSession()
    {
        $cartSession = $this->session->get('order_id', $this->currentOrder->getId());
        $this->session->set('order_id', $cartSession);

        $productCart = [];
        foreach ($this->currentOrder->getOrderItem() as $value) {
            $productCart[$value->getProduct()->getName()] = $value->getQuantity();
        }

        $this->session->set('product_list', $productCart);
    }
}
