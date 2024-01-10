<?php

namespace App\Controller;

use DateTime;
use App\Model\UserManager;
use App\Service\Cart\Cart;
use App\Model\OrderManager;
use App\Model\AddressManager;
use App\Model\ProductManager;
use App\Service\SessionManager;
use App\Model\OrderDetailsManager;

class OrderController extends AbstractController
{
    private $userManager;
    private $addressManager;
    private $orderManager;
    private $session;
    private $cart;
    private $orderDetailsManager;


    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->addressManager = new AddressManager();
        $this->orderManager = new OrderManager();
        $this->session = new SessionManager();
        $this->cart = new Cart(new SessionManager(), new ProductManager());
        $this->orderDetailsManager = new OrderDetailsManager();
    }

    public function index()
    {
        if (count($this->cart->show()) > 0) {
            if (!$this->userManager->hasAddress($_SESSION['user']['id'])) {
                $this->session->addFlash('error', 'Vous devez ajouter une adresse avant de passer commande');
                return $this->twig->render('address/add.html.twig', [
                    'session' => $this->session
                ]);
            }
            $addresses = $this->addressManager->selectAllAddressByUserId($_SESSION['user']['id']);
            $cartItems = $this->cart->show();
            $total = $this->cart->getTotal();
            return $this->twig->render('order/index.html.twig', [
                'addresses' => $addresses,
                'cart' => $cartItems,
                'total' => $total
            ]);
        } else {
            $this->session->addFlash('error', 'Votre panier est vide');
            return $this->twig->render('home/index.html.twig', [
                'session' => $this->session
            ]);
        }
    }

    public function orderAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = array_map('trim', $_POST);
            $order['user_id'] = $_SESSION['user']['id'];
            $order['created_at'] = date('Y-m-d H:i:s');
            $order['reference'] = date('Y-m-d') . uniqid();
            $order['address_id'] = $_POST['address_id'];

            $orderId = $this->orderManager->insert($order);
            $orderDetails = [];
            foreach ($this->cart->show() as $cartItem) {
                $orderDetail = [
                    'order_id' => $orderId,
                    'product_id' => $cartItem->product['id'],
                    'quantity' => $cartItem->qty,
                    'price_product' => $cartItem->product['price'],
                    'total' => $cartItem->product['price'] * $cartItem->qty,
                ];

                $orderDetails[] = $orderDetail;
            }
            $this->orderDetailsManager->insert($orderDetails);
            $this->cart->remove();
            header('Location: /order/show?id=' . $orderId);
        } else {
            header('Location: /order');
        }
    }


    public function show($id)
    {
        $id = $_SESSION['user']['id'];
        $order = $this->orderManager->selectLastOrder($id);
        $details = $this->orderDetailsManager->selectAllOrderDetails($order['order_id']);
        return $this->twig->render('order/show.html.twig', ['order' => $order, 'details' => $details]);
    }
}
