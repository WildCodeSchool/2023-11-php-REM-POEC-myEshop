<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\SessionManager;

class RegisterController extends AbstractController
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
    }
    public function register()
    {
        $session = $this->session;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }
            if(isset($_POST['lastname'],$_POST['firstname'], $_POST['email'],$_POST['password'],$_POST['c_password'])) {
                extract($_POST);

                if(empty($lastname)) {
                    $session->addFlash('danger', 'Le champs nom est requis');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(preg_match('/[0-9]/', $lastname)) {
                    $session->addFlash('danger', 'Le champs nom ne doit pas contenir de chiffres');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $lastname)) {
                    $session->addFlash('danger', 'Le champs nom ne doit pas contenir de caractères spéciaux');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(strlen($lastname) < 2) {
                    $session->addFlash('danger', 'Le champs nom doit contenir au moins 2 caractères');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                }

                if(empty($firstname)) {
                    $session->addFlash('danger', 'Le champs prénom est requis');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(preg_match('/[0-9]/', $firstname)) {
                    $session->addFlash('danger', 'Le champs prénom ne doit pas contenir de chiffres');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $firstname)) {
                    $session->addFlash('danger', 'Le champs prénom ne doit pas contenir de caractères spéciaux');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(strlen($firstname) < 2) {
                    $session->addFlash('danger', 'Le champs prénom doit contenir au moins 2
                caractères');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                }

                $user = new UserManager();

                if(empty($email)) {
                    $session->addFlash('danger', 'Le champs email est requis');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $session->addFlash('danger', 'Le champs email doit être un email valide');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif($user->verifEmail($email)) {
                    $session->addFlash('danger', 'Cet email est déjà utilisé');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                }

                if(empty($password)) {
                    $session->addFlash('danger', 'Le champs mot de passe est requis');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(strlen($password) < 8) {
                    $session->addFlash('danger', 'Le champs mot de passe doit contenir au
                    moins
                    8 caractères');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif($password !== $c_password) {
                    $session->addFlash('danger', 'Les mots de passe ne correspondent pas');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(!preg_match('/[A-Z]/', $password)) {
                    $session->addFlash('danger', 'Le mot de passe doit contenir au moins une majuscule');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(!preg_match('/[0-9]/', $password)) {
                    $session->addFlash('danger', 'Le mot de passe doit contenir au moins un chiffre');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                } elseif(!preg_match('/[\'^£$%&!*()}{@#~?><>,|=_+¬-]/', $password)) {
                    $session->addFlash('danger', 'Le mot de passe doit contenir au moins un caractère spécial');
                    return $this->twig->render('Register/index.html.twig', ['session' => $session]);
                }

                $user->insert([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_ARGON2I),
                'roles' => 'ROLE_USER'
                ]);

                $session->set('user', $user);
                $session->addFlash('success', 'Votre compte a bien été créé');
                return $this->twig->render('Login/index.html.twig');
            }
        }
        return $this->twig->render('Register/index.html.twig', ['session' => $session, 'post' =>
        $_POST]);
    }
}
