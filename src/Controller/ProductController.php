<?php

namespace App\Controller;

use App\Model\ProductManager;
use App\Service\SessionManager;

class ProductController extends AbstractController
{
    protected $productManager;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->productManager = new ProductManager();
        $this->session = new SessionManager();
    }
    public function index(): string
    {
        $products = $this->productManager->selectAll();
        return $this->twig->render('Product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
