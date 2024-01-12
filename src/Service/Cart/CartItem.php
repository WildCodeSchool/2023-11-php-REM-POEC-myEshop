<?php

namespace App\Service\Cart;

class CartItem
{
    public $qty;
    public $product;

    public function __construct($product, $qty)
    {
        $this->qty = $qty;
        $this->product = $product;
    }

    public function getTotal()
    {
        return $this->product['price'] * $this->qty;
    }
}
