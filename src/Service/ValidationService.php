<?php

namespace App\Service;

use App\Model\UserManager;
use App\Service\SessionManager;

/**
 * Class ValidationService
 *
 * This class provides validation functionality for various data inputs.
 */
class ValidationService
{
    protected $session;
    protected $userManager;

    /**
     * ValidationService constructor.
     *
     * @param SessionManager $session The session manager instance.
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
        $this->userManager = new UserManager();
    }

    /**
     * Translates a given key into its corresponding value from the translations array.
     *
     * @param string $key The key to be translated.
     * @return string The translated value if found, otherwise the original key.
     */
    private function translate($key): string
    {
        $translations = [
            'lastname' => 'nom',
            'firstname' => 'prénom',
            'password' => 'mot de passe',
            'c_password' => 'confirmation du mot de passe',
            'email' => 'email',
        ];

        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    /**
     * Validates the given post data.
     *
     * @param array $post The post data to validate.
     * @return bool Returns true if the post data is valid, false otherwise.
     */
    public function validatePost(array $post): bool
    {
        foreach ($post as $key => $value) {
            if (empty($value)) {
                $this->handleEmptyValue($key);
                return false;
            }

            if (!$this->validateField($key, $post)) {
                $this->session->set("border", "border border-danger");
                return false;
            }
        }

        return true;
    }

    /**
     * Validates a given field.
     *
     * @param string $key The key of the field to validate.
     * @param array $post The post data containing the field to validate.
     * @return bool Returns true if the field is valid, false otherwise.
     */
    private function validateField(string $key, array $post): bool
    {
        switch ($key) {
            case 'lastname':
            case 'firstname':
                return $this->validateRegistrationNameFields($post['lastname'], $post['firstname']);

            case 'email':
                return $this->validateRegistrationEmail($post['email']);

            case 'password':
            case 'c_password':
                return $this->validateRegistrationPassword($post['password'], $post['c_password']);
        }

        return true;
    }


    private function handleEmptyValue(string $key): void
    {
        $this->session->addFlash('danger', "Le champ {$this->translate($key)} est requis");
        $this->session->set("border", "border border-danger");
    }

    /**
     * Validates the registration text fields.
     *
     * @param string $lastname, $firstname The text fields to validate.
     * @return bool Returns true if the fields are valid, false otherwise.
     */
    public function validateRegistrationNameFields(string $lastname, string $firstname): bool
    {

        if (preg_match('/[0-9]/', $lastname) || preg_match('/[0-9]/', $firstname)) {
            $this->session->addFlash('danger', 'Les champs nom/prénom ne doivent pas contenir de chiffres');
            return false;
        }
        if (
            preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $lastname) ||
            preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $firstname)
        ) {
            $this->session->addFlash('danger', 'Les champs nom/prénom ne doivent pas contenir de caractères spéciaux');
            return false;
        }
        if (strlen($lastname) < 2 || strlen($firstname) < 2) {
            $this->session->addFlash('danger', 'Les champs nom/prénom
        doivent contenir au moins 2 caractères');
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
    public function validateRegistrationEmail(string $email): bool
    {
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
    public function validateRegistrationPassword(string $password, string $cPassword): bool
    {
        if (strlen($password) < 8) {
            $this->session->addFlash('danger', 'Le champs mot de passe doit contenir au
            moins 8
            caractères');
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->session->addFlash('danger', 'Le mot de passe doit contenir au moins une majuscule');
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->session->addFlash('danger', 'Le mot de passe doit contenir au moins un chiffre');
            return false;
        }
        if (!preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/', $password)) {
            $this->session->addFlash('danger', 'Le mot de passe doit contenir au moins un caractère spécial');
            return false;
        } if ($password !== $cPassword) {
            $this->session->addFlash('danger', 'Les mots de passe ne correspondent pas');
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
    public function validateLogin(string $email, string $password): bool
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

    public function validateCategory(array $fields): bool
    {
        foreach ($fields as $key => $value) {
            if (empty($value)) {
                $this->session->addFlash('danger', "Le champ {$key} est requis");
                $this->session->set("border", "border border-danger");
                return false;
            }
        }
        return true;
    }

    public function validateProduct(array $fields): bool
    {
        foreach ($fields as $key => $value) {
            if (empty($value)) {
                $this->session->addFlash('danger', "Le champ {$key} est requis");
                $this->session->set("border", "border border-danger");
                return false;
            }
        }
        return true;
    }
}
