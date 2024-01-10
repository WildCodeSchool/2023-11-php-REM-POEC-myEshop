<?php

namespace App\Controller;

use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Model\UserManager;

class LoginController extends AbstractController
{
    protected $session;
    protected $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->userManager = new UserManager();
    }

    public function login()
    {
        if ($this->session->isLogged()) {
            return $this->twig->render('Home/index.html.twig', ['session' => $this->session]);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $validationService = new ValidationService($this->session);
            if ($validationService->validateLogin($email, $password)) {
                $userManager = new UserManager();
                $user = $userManager->verifEmail($email);
                if ($user && password_verify($password, $user['password'])) {
                    $this->session->set('user', $user);
                    header('Location: /');
                    exit();
                } else {
                    $this->session->addFlash('danger', 'Identifiants invalides.');
                    return $this->twig->render('login/index.html.twig', ['session' => $this->session]);
                }
            }
            $this->session->addFlash('danger', 'Veuillez remplir tous les champs.');
            return $this->twig->render('login/index.html.twig', ['session' => $this->session]);
        }
        return $this->twig->render('login/index.html.twig', ['session' => $this->session]);
    }

    public function logout()
    {
        $this->session->logOut();
        header('Location: /');
    }
}
