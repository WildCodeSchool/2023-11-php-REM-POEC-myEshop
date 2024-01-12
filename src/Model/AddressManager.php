<?php

namespace App\Model;

class AddressManager extends AbstractManager
{
    public const TABLE = 'address';

    public function insert(array $address): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`user_id`, `name`,
         `firstname`, `lastname`, 
         `company`, `address`,`postal`,
         `city`, `country`,`phone`) 
        VALUES (:user_id, :name, 
        :firstname, :lastname, 
        :company, :address, :postal, 
        :city, :country, :phone)");
        $statement->bindValue('user_id', $address['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $address['name'], \PDO::PARAM_STR);
        $statement->bindValue('firstname', $address['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $address['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('company', $address['company'], \PDO::PARAM_STR);
        $statement->bindValue('address', $address['address'], \PDO::PARAM_STR);
        $statement->bindValue('postal', $address['postal'], \PDO::PARAM_STR);
        $statement->bindValue('city', $address['city'], \PDO::PARAM_STR);
        $statement->bindValue('country', $address['country'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $address['phone'], \PDO::PARAM_STR);

        $statement->execute();

        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $address): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name, 
        `firstname` = :firstname,
        `lastname` = :lastname, `company` = :company,
        `address` = :address, `postal` = :postal,
        `city` = :city, `country` = :country,
        `phone` = :phone WHERE id=:id");
        $statement->bindValue('id', $address['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $address['name'], \PDO::PARAM_STR);
        $statement->bindValue('firstname', $address['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $address['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('company', $address['company'], \PDO::PARAM_STR);
        $statement->bindValue('address', $address['address'], \PDO::PARAM_STR);
        $statement->bindValue('postal', $address['postal'], \PDO::PARAM_STR);
        $statement->bindValue('city', $address['city'], \PDO::PARAM_STR);
        $statement->bindValue('country', $address['country'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $address['phone'], \PDO::PARAM_STR);


        return $statement->execute();
    }

    public function selectAllAddressByUserId(int $id): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE user_id= :id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":id", $id, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }
}
