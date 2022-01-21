<?php

namespace App\Service;

class CartManager extends CartOrderService
{
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
}
