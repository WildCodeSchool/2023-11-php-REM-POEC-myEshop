<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new user in database
     */
    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " 
        (`firstname`, `lastname`, `email`, `password`, `roles`, `avatar`) 
        VALUES (:firstname, :lastname, :email, :password, :roles, :avatar)");
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('roles', $user['roles'], \PDO::PARAM_STR);
        $statement->bindValue(':avatar', $user['avatar'], \PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update user in database
     */
    public function update(array $user): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
        " SET firstname = :firstname, lastname = :lastname, 
        email = :email, avatar = :avatar 
        WHERE id=:id");
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue(':avatar', $user['avatar'], \PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Verifies if an email exists in the database.
     *
     * @param string $email The email to verify.
     * @return array|false The user data if the email exists, false otherwise.
     */
    public function verifEmail($email): array|false
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function hasAddress(int $id): bool
    {
        $statement = $this->pdo->prepare("SELECT * FROM address WHERE user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}
