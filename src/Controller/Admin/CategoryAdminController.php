<?php

namespace App\Controller\Admin;

use App\Model\CategoryManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;

class CategoryAdminController extends AbstractController
{
    protected $session;
    protected $validationService;
    protected $categoryManager;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
        $this->categoryManager = new CategoryManager();
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
                header('Location: /admin/category');
                exit();
            }
        }
        return $this->twig->render('admin/category/create.html.twig', [
            'session' => $this->session,
            'category' => $category ?? null,
        ]);
    }

    public function show($id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $categoryManager = $this->categoryManager;
        $category = $categoryManager->selectOneById($id);
        return $this->twig->render('admin/category/show.html.twig', [
            'category' => $category,
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
                header('Location: /admin/category');
                exit();
            }
        }
        return $this->twig->render('admin/category/update.html.twig', [
            'category' => $categoryManager->selectOneById($id),
        ]);
    }


    public function delete(): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryManager = $this->categoryManager;
            $categoryId = is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
            $categoryManager->delete($categoryId);
            header('Location: /admin/category');
            exit();
        }
        return $this->twig->render(
            'admin/category/delete.html.twig',
            [
                'category' => $this->categoryManager->selectOneById($_GET['id']),
            ]
        );
    }
}
