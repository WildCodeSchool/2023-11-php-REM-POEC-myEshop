<?php

namespace App\Controller;

class LoginController extends AbstractController
{
    public function login()
    {
        return $this->twig->render('Login/index.html.twig');
    }
}