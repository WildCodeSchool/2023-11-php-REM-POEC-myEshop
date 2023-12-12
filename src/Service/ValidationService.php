<?php

namespace App\Service;

use App\Service\SessionManager;

/**
 * Class ValidationService
 *
 * This class provides validation functionality for various data inputs.
 */
class ValidationService
{
    protected $session;

    /**
     * ValidationService constructor.
     *
     * @param SessionManager $session The session manager instance.
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Validates the registration text fields.
     *
     * @param string $fields The text fields to validate.
     * @return bool Returns true if the fields are valid, false otherwise.
     */
    public function validateRegistrationTextFields($fields)
    {
        if (empty($fields)) {
            $this->session->addFlash('danger', 'Le champs nom est requis');
            return false;
        }
        if (preg_match('/[0-9]/', $fields)) {
            $this->session->addFlash('danger', 'Les champs nom et prénom ne doivent pas contenir de chiffres');
            return false;
        }
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $fields)) {
            $this->session->addFlash('danger', 'Les champs nom/prénom ne doivent pas contenir de caractères spéciaux');
            return false;
        }
        if (strlen($fields) < 2) {
            $this->session->addFlash('danger', 'Les champs nom/prénom doivent contenir au moins 2
        caractères');
            return false;
        } else {
            return true;
        }
    }

    /**
    * Validates the registration email.
    *
    * @param string $email The email to validate.
    * @return bool Returns true if the email is valid, false otherwise.
    */
    public function validateRegistrationEmail($email)
    {
        if (empty($email)) {
            $this->session->addFlash('danger', 'Le champs email est requis');
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->addFlash('danger', 'Le champs email doit être un email valide');
            return false;
        } else {
            return true;
        }
    }

    /**
    * Validates the registration password.
    *
    * @param string $password The password to validate.
    * @param string $cPassword The confirmation password.
    * @return bool Returns true if the password is valid, false otherwise.
    */
    public function validateRegistrationPassword($password, $cPassword)
    {
        if (empty($password)) {
            $this->session->addFlash('danger', 'Le champs mot de passe est requis');
            return false;
        }
        if (strlen($password) < 8) {
            $this->session->addFlash('danger', 'Le champs mot de passe doit contenir au moins 8
            caractères');
            return false;
        }
        if ($password !== $cPassword) {
            $this->session->addFlash('danger', 'Les mots de passe ne correspondent pas');
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->session->addFlash('danger', 'Le mot de passe doit contenir au moins une majuscule');
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->session->addFlash('danger', 'Le mot de passe doit contenir au moins un chiffre');
            return false;
        } else {
            return true;
        }
    }

    /**
    * Validates the login credentials.
    *
    * @param string $email The login email.
    * @param string $password The login password.
    * @return bool Returns true if the credentials are valid, false otherwise.
    */
    public function validateLogin($email, $password)
    {
        if (empty($email)) {
            $this->session->addFlash('danger', 'Le champs email est requis');
            return false;
        } elseif (empty($password)) {
            $this->session->addFlash('danger', 'Le champs mot de passe est requis');
            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->addFlash('danger', 'Le champs email doit être un email valide');
            return false;
        } else {
            return true;
        }
    }
}
