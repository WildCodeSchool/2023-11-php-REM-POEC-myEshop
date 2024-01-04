<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;

class UserController extends AbstractController
{
    protected $userManager;
    protected $session;
    protected $validationService;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
    }
    public function index(): string
    {
        $user = $this->session->get('user');
        return $this->twig->render('user/profile.html.twig', [
            'user' => $user,

        ]);
    }

    public function update(): string
    {
        $userManager = new UserManager();


        $user = $this->session->get('user');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }

            if (!$this->validationService->validatePost($_POST)) {
                return $this->twig->render('user/update.html.twig', [
                    'user' => $user
                ]);
            }

            $userUpdate = [
                'id' => $_POST['id'],
                'lastname' => $_POST['lastname'],
                'firstname' => $_POST['firstname'],
                'email' => $user['email'],
                'password' => $user['password'],
                'roles' => $user['roles'],
            ];


            $userManager->update($userUpdate);
            $this->session->remove('user');
            $this->session->set('user', $userUpdate);

            return $this->twig->render('user/profile.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->twig->render('user/update.html.twig', [
            'user' => $user,
        ]);
    }
}
