<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function showPanel(): string
    {
        return $this->twig->render('AdminPanel/index.html.twig', []);
    }
}
