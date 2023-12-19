<?php

namespace App\Controller\Admin;

use App\Model\ProductManager;
use App\Model\CategoryManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;

class CategoryAdminController extends AbstractController
{
    protected $session;
    protected $validationService;
    protected $categoryManager;
    protected $productManager;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
        $this->categoryManager = new CategoryManager();
        $this->productManager = new ProductManager();
    }
    public function index(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $categoryManager = $this->categoryManager;
        $categories = $categoryManager->selectAll();
        return $this->twig->render('admin/category/index.html.twig', [
            'categories' => $categories,
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
            $category = array_map('trim', $_POST);
            $this->validationService->validateCategory($category);
            if ($this->validationService->validateCategory($category)) {
                $this->categoryManager->insert($category);
                header('Location: /admin/category');
                exit();
            }
        }
        return $this->twig->render('admin/category/create.html.twig', [
            'session' => $this->session
        ]);
    }

    public function show(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $categoryManager = $this->categoryManager;
        $category = $categoryManager->selectOneById($id);
        return $this->twig->render('admin/category/show.html.twig', [
            'category' => $category,
            'session' => $this->session
        ]);
    }

    public function update(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $categoryManager = $this->categoryManager;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = array_map('trim', $_POST);
            $this->validationService->validateCategory($category);
            if ($this->validationService->validateCategory($category)) {
                $categoryManager->update($category);
                $this->session->addFlash('success', "La catégorie $category[name] a bien été modifiée");
                header('Location: /admin/category');
                exit();
            }
        }
        return $this->twig->render('admin/category/update.html.twig', [
            'category' => $categoryManager->selectOneById($id),
            'session' => $this->session
        ]);
    }


    public function delete(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryManager = $this->categoryManager;
            $id = is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
            $categoryManager->delete($id);
            $this->session->addFlash('success', "La catégorie numéro $id a bien été supprimée");
            header('Location: /admin/category/');
            exit();
        }
        return $this->twig->render(
            'admin/category/delete.html.twig',
            [
                'category' => $this->categoryManager->selectOneById($id),
            ]
        );
    }

    public function searchCategory(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $categoryManager = $this->categoryManager;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryArray = array_map('trim', $_POST);
            $category = $categoryArray['category'];
            $categories = $categoryManager->searchC($category);

            if ($categories) {
                return $this->twig->render('admin/category/search.html.twig', [
                    'categories' => $categories ,
                    'session' => $this->session
                ]);
            } else {
                header('Location: /admin/category/');
                exit();
            }
        }

        return $this->twig->render('includes/_sidebar_left_admin.html.twig', [
            'session' => $this->session
        ]);
    }
}
