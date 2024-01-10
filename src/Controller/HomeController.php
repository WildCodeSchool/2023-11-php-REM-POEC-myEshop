<?php

namespace App\Controller;

use App\Model\ProductManager;
use App\Service\SessionManager;

class HomeController extends AbstractController
{
    protected $session;
    protected $productManager;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->productManager = new ProductManager();
    }

    /**
     * Display home page
     */
    public function index(): string
    {
        $products = $this->productManager->selectAllWithCategory();
        $lastProducts = $this->productManager->selectLastProducts();
        return $this->twig->render('Home/index.html.twig', [
            'session' => $this->session,
            'products' => $products,
            'lastProducts' => $lastProducts
        ]);
    }
}
