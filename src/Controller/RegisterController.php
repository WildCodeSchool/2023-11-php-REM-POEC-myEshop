<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\SessionManager;
use App\Service\ValidationService;

class RegisterController extends AbstractController
{
    protected $session;
    protected $validationService;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
    }
    public function register()
    {
        $session = $this->session;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }
            if (
                isset(
                    $_POST['lastname'],
                    $_POST['firstname'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['c_password']
                )
            ) {
                if (!$this->validationService->validatePost($_POST)) {
                    return $this->twig->render('register/index.html.twig', [
                        'session' => $session,
                    ]);
                }
                $lastname = $_POST['lastname'] ?? '';
                $firstname = $_POST['firstname'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                $user = new UserManager();
                $user->insert([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_ARGON2I),
                    'roles' => 'ROLE_USER'
                ]);
                $session->addFlash('success', 'Votre compte a bien été créé');
                header('Location: /login');
                exit();
            }
            return $this->twig->render('register/index.html.twig', [
                'session' => $session
            ]);
        }
        return $this->twig->render('Register/index.html.twig', [
            'session' => $session
        ]);
    }
}
