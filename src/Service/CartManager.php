<?php

namespace App\Service;

class CartManager extends CartOrderService
{
    /**
     * @return void
     */
    public function clearCart()
    {
        $this->removeOrder();
        $this->session->clear();
    }
}
