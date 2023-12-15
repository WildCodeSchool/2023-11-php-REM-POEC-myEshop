<?php

namespace App\Controller\Admin;

use App\Model\ProductManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;

class AdminProductController extends AbstractController
{
    protected $session;
    protected ProductManager $productManager;
    protected $validationService;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->productManager = new ProductManager();
        $this->validationService = new ValidationService($this->session);
    }

    public function index(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        $products = $productManager->selectAll();
        return $this->twig->render('Admin/product/index.html.twig', [
            'products' => $products,
            'session' => $this->session
        ]);
    }

    public function create(): string
    {
        if ($this->session->isAdmin() === false) {
            header('Location:/');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = array_map('trim', $_POST);
            $this->validationService->validateProduct($product);
            if ($this->validationService->validateProduct($product)) {
                $this->productManager->insert($product);
                header('Location: /admin/product');
                exit();
            }
        }
        return $this->twig->render('admin/product/create.html.twig', [
            'session' => $this->session
        ]);
    }

    public function show(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        $product = $productManager->selectOneById($id);
        return $this->twig->render('admin/product/show.html.twig', [
            'product' => $product,
            'session' => $this->session
        ]);
    }

    public function update(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = array_map('trim', $_POST);
            $this->validationService->validateProduct($product);
            if ($this->validationService->validateProduct($product)) {
                $productManager->update($product);
                $this->session->addFlash('success', "Le produit $product[name] a bien été modifié");
                header('Location: /admin/product');
                exit();
            }
        }
        return $this->twig->render('admin/product/update.html.twig', [
            'product' => $productManager->selectOneById($id),
            'session' => $this->session
        ]);
    }

    public function delete(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        $product = $productManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productManager->delete($id);
            $this->session->addFlash('success', "Le produit $product[name] a bien été supprimé");
            header('Location: /admin/product');
            exit();
        }
        return $this->twig->render('admin/product/delete.html.twig', [
            'product' => $product,
            'session' => $this->session
        ]);
    }

    public function searchProduct($keyword): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productArray = array_map('trim', $_POST);
            $product = $productArray['product'];
            $keyword = $productManager->searchP($product);
            return $this->twig->render('admin/product/search.html.twig', [
                'product' => $keyword,
                'session' => $this->session
            ]);
        }
        return $this->twig->render('base.html.twig', [
            'session' => $this->session
        ]);
    }
}
