<?php

namespace App\Service;

use App\Entity\Product;

class OrderManager extends CartOrderService
{
    /**
     * @param Product $product
     * @param OrderItemFormType  $form
     * @return void
     */
    public function addItemToCurrentOrder($product, $form)
    {
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

        $this->saveOrderToDatabase();
    }

    /**
     * @param Product $product
     * @return bool
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
    public function saveOrderToDatabase()
    {
        $this->entityManager->persist($this->currentOrder);
        $this->getOrderTotal();
        $this->entityManager->flush();
        $this->saveOrderToSession();
    }

    /**
     * @return void
     */
    public function saveOrderToSession()
    {
        $cartSession = $this->session->get('order_id', $this->currentOrder->getId());
        $this->session->set('order_id', $cartSession);
        $this->updateCartContent();
    }
}
