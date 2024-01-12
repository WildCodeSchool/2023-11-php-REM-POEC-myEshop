<?php

namespace App\Controller\Admin;

use App\Model\UserManager;
use App\Service\SessionManager;
use App\Service\ValidationService;
use App\Controller\AbstractController;

class AdminUserController extends AbstractController
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
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $users = $this->userManager->selectAll();
        return $this->twig->render('admin/user/index.html.twig', ['users' => $users]);
    }

    public function update(int $id): string
    {
        if (!$this->session->isAdmin()) {
            header('Location:/');
            exit();
        }
        $session = $this->session;

        $userManager = new UserManager();

        $user = $userManager->selectOneById($id);

        if (!$user) {
            $session->addFlash('danger', 'Utilisateur non trouvé');
            return $this->twig->render('admin/user/index.html.twig', [
                'session' => $session,
            ]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = trim($value);
            }

            if (!$this->validationService->validatePost($_POST)) {
                return $this->twig->render('admin/user/update.html.twig', [
                    'session' => $session,
                ]);
            }

            $userUpdate = [
                'id' => $_POST['id'],
                'lastname' => $_POST['lastname'],
                'firstname' => $_POST['firstname'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'roles' => $_POST['roles'],
            ];


            $userManager->update($userUpdate);

            $session->addFlash('success', 'Votre compte a bien été mis à jour');
            header('Location: /admin/user');
            exit();
        }

        return $this->twig->render('admin/user/update.html.twig', [
            'user' => $user,
            'session' => $session,
        ]);
    }
}
