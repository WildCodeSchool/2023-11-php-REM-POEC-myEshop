<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;
use App\Service\Utils;

class UserController extends AbstractController
{
    protected $userManager;
    protected $session;
    protected $validationService;
    protected $utils;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->session = new SessionManager();
        $this->validationService = new ValidationService($this->session);
        $this->utils = new Utils();
    }
    public function index(): string
    {
        $user = $this->session->get('user');
        return $this->twig->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    public function update(int $id): string
    {
        $session = $this->session;
        $userManager = new UserManager();
        $user = $this->session->get('user');
        $id = $user['id'];

        if ($id != $_GET['id']) {
            $this->session->addFlash('danger', 'Vous devez vous connecter!');

            header('Location: /');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }
            if (
                isset(
                    $_POST['lastname'],
                    $_POST['firstname'],
                    $_POST['email']
                )
            ) {
                if (!$this->validationService->validatePost($_POST)) {
                    return $this->twig->render('user/update.html.twig', [
                        'user' => $user,
                        'session' => $session
                    ]);
                }
                $photoProfil = $this->utils->updatePhotoProfil($_FILES['avatar']);

                $userUpdate = [
                    'id' => $id,
                    'lastname' => $_POST['lastname'],
                    'firstname' => $_POST['firstname'],
                    'email' => $user['email'],
                    'avatar' => $photoProfil
                ];

                $userManager->update($userUpdate);
                $this->session->remove('user');
                $this->session->set('user', $userUpdate);
                header('Location: /profile');
                return $this->twig->render('user/profile.html.twig', [
                    'user' => $user,
                ]);
            }
        }
        return $this->twig->render('user/update.html.twig', [
            'user' => $user,
        ]);
    }
}
