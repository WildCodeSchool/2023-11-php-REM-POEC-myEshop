<?php

namespace App\Controller;

use App\Model\ContactManager;

class ContactController extends AbstractController
{
    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact = array_map('trim', $_POST);
            $contactManager = new ContactManager();
            $contactManager->insert($contact);
            $_SESSION['success_message'] = 'Votre message a été envoyé.
             Nous traiterons votre demande dans les plus brefs délais.';

            header('Location:/contact/add');
            $_SESSION['success_message'] = 'Votre message a été envoyé. 
            Nous traiterons votre demande dans les plus brefs délais.';
            return null;
        }

        $successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
        unset($_SESSION['success_message']);

        return $this->twig->render('Contact/index.html.twig', ['successMessage' => $successMessage]);
    }
}
