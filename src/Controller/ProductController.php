<?php

namespace App\Controller;

use App\Service\PaginatorService as Paginator;
use App\Model\ProductManager;
use App\Service\SessionManager;

class ProductController extends AbstractController
{
    protected $productManager;
    protected $session;
    protected $paginator;

    public function __construct()
    {
        parent::__construct();
        $this->productManager = new ProductManager();
        $this->session = new SessionManager();
        $this->paginator = new Paginator();
    }

    public function index(): string
    {
        $data = $this->productManager->selectAllWithCategory();
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 8;
        $products = $this->paginator->paginate($data, $page, $perPage);

        return $this->twig->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    public function show(int $id): string
    {
        if (!is_numeric($id) || intval($id) != $id || empty($id)) {
            header("Location: /product");
            exit();
        }

        $productManager = $this->productManager;
        $product = $productManager->selectOneByIdWithCategory($id);
        if (!$product) {
            header("Location: /product");
            exit();
        }
        $comments = $productManager->getAllCommentsProductId($id);
        return $this->twig->render('product/show.html.twig', [
            'product' => $product,
            'comments' => $comments,
            'session' => $this->session
        ]);
    }

    public function searchProduct(): string
    {
        $productManager = $this->productManager;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productArray = array_map('trim', $_POST);
            $product = $productArray['product'];
            $keyword = $productManager->searchP($product);
            $data = $keyword;
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $perPage = 8;
            $products = $this->paginator->paginate($data, $page, $perPage);

            if ($keyword) {
                return $this->twig->render('/product/search.html.twig', [
                    'products' => $products,
                    'session' => $this->session
                ]);
            } else {
                header('Location: /product');
                exit();
            }
        }
        return $this->twig->render('/product/index.html.twig', [
            'session' => $this->session
        ]);
    }
}
