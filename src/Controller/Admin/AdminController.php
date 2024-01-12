<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;

class AdminController extends AbstractController
{
    public function index(): string
    {
        return $this->twig->render('admin/index.html.twig', []);
    }
}
