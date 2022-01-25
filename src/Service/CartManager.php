<?php

namespace App\Service;

class CartManager extends CartOrderService
{
    public function clearCart()
    {
        $this->removeOrder();
        $this->session->clear();
    }
}
