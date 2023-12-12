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
                $lastname = $_POST['lastname'] ?? '';
                $firstname = $_POST['firstname'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $cPassword = $_POST['c_password'] ?? '';

                $error = true;
                $isLastNameValid = $this->validationService->validateRegistrationTextFields($lastname);
                $isFirstNameValid = $this->validationService->validateRegistrationTextFields($firstname);
                $isEmailValid = $this->validationService->validateRegistrationEmail($email);
                $isPasswordValid = $this->validationService->validateRegistrationPassword($password, $cPassword);

                if ($isLastNameValid && $isFirstNameValid && $isEmailValid && $isPasswordValid) {
                    $user = new UserManager();
                    $userId = $user->insert([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_ARGON2I),
                    'roles' => 'ROLE_USER'
                    ]);

                    $userData = [
                        'id' => $userId,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'roles' => 'ROLE_USER'
                    ];

                    $error = false;
                    $session->set('user', $userData);
                    $session->addFlash('success', 'Votre compte a bien été créé');
                    return $this->twig->render('login/index.html.twig', [
                        'session' => $session,
                        'error' => $error
                    ]);
                } else {
                    return $this->twig->render('register/index.html.twig', [
                        'session' => $session,
                        'error' => $error
                        ]);
                }
            }
            return $this->twig->render('Register/index.html.twig');
        }
        return $this->twig->render('Register/index.html.twig');
    }
}
