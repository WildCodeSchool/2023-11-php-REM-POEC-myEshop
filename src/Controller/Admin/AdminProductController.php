<?php

namespace App\Controller\Admin;

use App\Model\ProductManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;
use App\Service\UploadedFileValidationService;

class AdminProductController extends AbstractController
{
    protected $session;
    protected ProductManager $productManager;
    protected $validationService;
    protected $fileUploadService;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->productManager = new ProductManager();
        $this->validationService = new ValidationService($this->session);
        $this->fileUploadService = new UploadedFileValidationService();
    }

    public function index(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $productManager = $this->productManager;
        $products = $productManager->selectAllWithCategory();
        return $this->twig->render('Admin/product/index.html.twig', [
            'products' => $products,
            'session' => $this->session
        ]);
    }

    public function create(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = array_map('trim', $_POST);
            $illustrationFile = $_FILES['illustration'];

            if ($this->validationService->validateProduct($product)) {
                $uploadResult = $this->fileUploadService->validateFileUpload($illustrationFile);

                if ($uploadResult['success']) {
                    $this->productManager->insert($product, $uploadResult['uploadFile']);
                    $this->session->addFlash('success', 'Le produit a bien été ajouté');
                    header('Location: /admin/product');
                    exit();
                } else {
                    return $this->twig->render('/admin/product/create.html.twig', [
                    'session' => $this->session,
                    ]);
                }
            }
        }
        return $this->twig->render('admin/product/create.html.twig', [
        'session' => $this->session,
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
        $productToUpdate = $productManager->selectOneById($id);
        if (!$productToUpdate) {
            $this->session->addFlash('danger', 'Le produit demandé n\'existe pas');
            return $this->twig->render('admin/product.html.twig', [
                'session' => $this->session
            ]);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = array_map('trim', $_POST);
            $illustrationFile = $_FILES['illustration'];
            if ($this->validationService->validateProduct($product)) {
                $currentIllustration = $productToUpdate['illustration'];
                if (!empty($illustrationFile['name'])) {
                    if (
                        $this->fileUploadService->
                        validateUpdatedFile($illustrationFile, $currentIllustration)
                    ) {
                        $uploadFile = $this->fileUploadService->getUploadFile();
                    } else {
                        $uploadFile = $currentIllustration;
                    }
                } else {
                    $uploadFile = $currentIllustration;
                }
                $this->productManager->update($productToUpdate, $uploadFile);
                $this->session->addFlash('success', 'Le produit a bien été modifié');
                header('Location: /admin/product');
                exit();
            } else {
                $this->session->addFlash('danger', 'Validation du produit échouée');
            }
        }
        return $this->twig->render('admin/product/update.html.twig', [
            'product' => $productToUpdate,
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

    public function searchProduct(): string
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

            if ($keyword) {
                return $this->twig->render('admin/product/search.html.twig', [
                    'products' => $keyword,
                    'session' => $this->session
                ]);
            } else {
                header('Location: /admin/product');
                exit();
            }
        }
        return $this->twig->render('includes/_sidebar_left_admin.html.twig', [
            'session' => $this->session
        ]);
    }
}
