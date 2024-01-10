<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Service\Utils;

class RegisterController extends AbstractController
{
    protected $session;
    protected $validationService;
    protected $utils;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
        $this->utils = new Utils();
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
                    $_POST['c_password'],
                )
            ) {
                $lastname = $_POST['lastname'] ?? '';
                $firstname = $_POST['firstname'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                $photoProfil = $this->utils->uploadPhotoProfil($_FILES['avatar']);

                if (!$this->validationService->validatePost($_POST)) {
                    return $this->twig->render('register/index.html.twig', [
                        'session' => $session,
                    ]);
                }

                $user = new UserManager();
                if ($user->verifEmail($email)) {
                    $session->addFlash('danger', 'Cet email est déjà utilisé');
                    return $this->twig->render('register/index.html.twig', [
                        'session' => $session,
                    ]);
                }

                $user->insert([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_ARGON2I),
                    'roles' => 'ROLE_USER',
                    'avatar' => $photoProfil
                ]);
                header('Location: /login');
                exit();
            }
            return $this->twig->render('register/index.html.twig', [
                'session' => $session,
            ]);
        }
        return $this->twig->render('Register/index.html.twig', [
            'session' => $session,
        ]);
    }
}
