<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Service\SessionManager;

class HomeController extends AbstractController
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
    }

    /**
     * Display home page
     */
    public function index(): string
    {

        return $this->twig->render('Home/index.html.twig', [
            'session' => $this->session,
        ]);
    }
}
