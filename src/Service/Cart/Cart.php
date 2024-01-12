<?php

namespace App\Service\Cart;

class Cart
{
    private $sessionManager;
    private $productManager;
    private $cartItems = [];

    public function __construct($sessionManager, $productManager)
    {
        $this->sessionManager = $sessionManager;
        $this->productManager = $productManager;
        $this->cartItems = $this->sessionManager->get('cart', []);
    }

    public function add(int $id)
    {
        if (array_key_exists($id, $this->cartItems)) {
            $this->cartItems[$id]++;
        } else {
            $this->cartItems[$id] = 1;
        }
        $this->sessionManager->set('cart', $this->cartItems);
    }

    public function deleteOneProductInCart(int $id)
    {
        if (array_key_exists($id, $this->cartItems)) {
                unset($this->cartItems[$id]);
        }
        $this->sessionManager->set('cart', $this->cartItems);
    }

    public function cartProductDecrement(int $id)
    {
        if (array_key_exists($id, $this->cartItems)) {
            if ($this->cartItems[$id] > 1) {
                $this->cartItems[$id]--;
            } else {
                unset($this->cartItems[$id]);
            }
        }
        $this->sessionManager->set('cart', $this->cartItems);
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->sessionManager->get('cart', []) as $id => $qty) {
             $product = $this->productManager->selectOneById($id);
            if (!$product) {
                continue;
            }
            $total += ($product['price'] * $qty);
        }
        return $total;
    }

    public function show()
    {
        $cart = [];

        foreach ($this->sessionManager->get('cart', []) as $id => $qty) {
            $product = $this->productManager->selectOneById($id);
            if (!$product) {
                continue;
            }
             $cart[] = new CartItem($product, $qty);
        }

        return $cart;
    }

    public function remove()
    {
        $this->sessionManager->remove('cart');
    }
}
