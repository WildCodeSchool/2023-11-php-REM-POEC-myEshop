<?php

namespace App\Controller ;

use App\Model\CommentManager;
use App\Service\SessionManager;

class CommentController extends AbstractController
{
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = new SessionManager();
    }

    public function add()
    {
        $user = $this->session->isLogged();
        $errors = [];

        if (!$user) {
            $msgError = "Vous devez être connecté pour poster un commentaire" ;
            return $this->twig->render('Comments/addComment.html.twig', [
                'msgEreur' => $msgError
            ]);
        } else {
            $user = $this->session->get('user');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $credentials = array_map('trim', $_POST);

                if ($credentials['comment'] === '') {
                    $errors[] = "Veillez saisir un commentaire";
                }

                if ($errors) {
                    return $this->twig->render('Comments/addComment.html.twig', [
                        'errors' => $errors
                    ]);
                } else {
                    $credentials['content'] = $credentials['comment'];
                    $credentials['user_id'] = $user['id'];
                    $credentials['product_id'] = $_GET['id'];
                    $credentials['created_at'] = date("Y-m-d");

                    $commentMenager = new CommentManager();
                    $commentMenager->insert($credentials);

                    header('Location: /product/show?id=' . $_GET['id']);
                }
            }
            return $this->twig->render('Comments/addComment.html.twig');
        }
    }
}
