<?php

namespace App\Model;

use PDO;

class ContactManager extends AbstractManager
{
    public const TABLE = 'contact';

    /**
     * Insert new message in database
     */
    public function insert(array $contact): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        " (`firstname`, `lastname`, `email`, `phone`, `message`) 
        VALUES (:firstname, :lastname, :email, :phone, :message)");
        $statement->bindValue('firstname', $contact['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $contact['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $contact['email'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $contact['phone'], \PDO::PARAM_STR);
        $statement->bindValue('message', $contact['message'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
