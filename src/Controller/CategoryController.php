<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Service\SessionManager;
use App\Service\PaginatorService;

class CategoryController extends AbstractController
{
    protected $categoryManager;
    protected $session;
    protected $paginator;

    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = new CategoryManager();
        $this->session = new SessionManager();
        $this->paginator = new PaginatorService();
    }

    public function index(int $id): string
    {

        $data = $this->categoryManager->selectAllProductByCategoryId($id);
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 8;
        $products = $this->paginator->paginate($data, $page, $perPage);
        return $this->twig->render("Category/index.html.twig", ['products' => $products]);
    }
}
