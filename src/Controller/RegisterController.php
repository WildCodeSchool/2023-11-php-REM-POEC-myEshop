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

                $isLastNameValid = $this->validationService->validateRegistrationTextFields($lastname);
                $isFirstNameValid = $this->validationService->validateRegistrationTextFields($firstname);
                $isEmailValid = $this->validationService->validateRegistrationEmail($email);
                $isPasswordValid = $this->validationService->validateRegistrationPassword($password, $cPassword);

                if ($isLastNameValid && $isFirstNameValid && $isEmailValid && $isPasswordValid) {
                    $user = new UserManager();
                    $user->insert([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'password' => password_hash($password, PASSWORD_ARGON2I),
                        'roles' => 'ROLE_USER'
                    ]);

                    $session->set('user', $user);
                    $session->addFlash('success', 'Votre compte a bien été créé');
                    return $this->twig->render('Login/index.html.twig', ['session' => $session]);
                } else {
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                }
            }
            return $this->twig->render('Register/index.html.twig');
        }
        return $this->twig->render('Register/index.html.twig');
    }
}
