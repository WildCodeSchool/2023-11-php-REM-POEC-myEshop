<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Service\SessionManager;

class CategoryController extends AbstractController
{
    protected $categoryManager;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = new CategoryManager();
        $this->session = new SessionManager();
    }

    public function index(int $id): string
    {
        $products = $this->categoryManager->selectAllProductByCategoryId($id);

        return $this->twig->render("Category/index.html.twig", ['products' => $products]);
    }
}
