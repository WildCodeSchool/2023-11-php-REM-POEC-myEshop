<?php

namespace App\Controller;

use App\Service\Cart\Cart;
use App\Model\ProductManager;
use App\Service\SessionManager;

class CartController extends AbstractController
{
    private $cart;
    public function __construct()
    {
        parent::__construct();
        $this->cart = new Cart(new SessionManager(), new ProductManager());
    }

    public function index()
    {
        $cartItems = $this->cart->show();
        $total = $this->cart->getTotal();
        return $this->twig->render('Cart/index.html.twig', [
            'cart' => $cartItems,
            'total' => $total
        ]);
    }

    public function add(int $id)
    {
        $this->cart->add($id);
        header('Location: /cart');
    }

    public function decrement(int $id)
    {
        $this->cart->cartProductDecrement($id);
        header('Location: /cart');
    }

    public function deleteOneProductInCart(int $id)
    {
        $this->cart->deleteOneProductInCart($id);
        header('Location: /cart');
    }

    public function cartRemove()
    {
        $this->cart->remove();
        header('Location: /cart');
    }
}
