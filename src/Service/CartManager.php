<?php

namespace App\Service;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class CartManager
{
    /**
     *
     * @var Session
     */
    private $session;

    /**
     * @var Orders
     */
    private $currentOrder;

    function __construct(OrdersRepository $ordersRepository, RequestStack $requestStack)
    {
        $this->orderRepository = $ordersRepository;
        $this->session = $requestStack->getSession();
        $this->getCurrentOrder();
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
     * @return void
     */
    public function updateCartProductList()
    {

        $productCart = [];
        foreach ($this->currentOrder->getOrderItem() as $itemOrder) {
            $productCart[$itemOrder->getProduct()->getName()] = $itemOrder->getQuantity();
        }

        $this->session->set('product_list', $productCart);
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

        return $total;
    }
}
