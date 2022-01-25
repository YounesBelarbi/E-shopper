<?php

namespace App\Service;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;


class CartOrderService
{
    /**
     *
     * @var OrdersRepository
     */
    protected $ordersRepository;

    /**
     *
     * @var Session
     */
    protected $session;

    /**
     *
     * @var ManagerRegistry
     */
    protected $entityManager;

    /**
     * @var Orders
     */
    protected $currentOrder;

    function __construct(OrdersRepository $ordersRepository, RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->orderRepository = $ordersRepository;
        $this->session = $requestStack->getSession();
        $this->entityManager = $doctrine->getManager();
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

    public function updateCartContent()
    {
        $productCart = [];
        foreach ($this->currentOrder->getOrderItem() as $value) {

            $productCart[$value->getProduct()->getName()]['product'] = $value->getProduct();
            $productCart[$value->getProduct()->getName()]['quantity'] = $value->getQuantity();
            $productCart[$value->getProduct()->getName()]['itemOrderId'] = $value->getId();
        }

        $this->session->set('product_list', $productCart);
    }

    public function removeOrder()
    {
        $this->entityManager->remove($this->currentOrder);
        $this->entityManager->flush();
    }
}
