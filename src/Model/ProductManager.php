<?php

namespace App\Model;

use PDO;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    /**
     * Insert new item in database
     */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, `category_id`,
         `illustration`, `description`, `price`, `stock`) 
         VALUES 
         (:name,:category_id,:illustration,
         :description,:price,:stock)");
        $statement->bindValue('name', $item['name'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $item['category_id'], PDO::PARAM_INT);
        $statement->bindValue('illustration', $item['illustration'], PDO::PARAM_STR);
        $statement->bindValue('description', $item['description'], PDO::PARAM_STR);
        $statement->bindValue('price', $item['price'], PDO::PARAM_INT);
        $statement->bindValue('stock', $item['stock'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name,
         `category_id` = :category_id,
         `illustration` = :illustration,
         `description` = :description,
         `price` = :price, `stock` = :stock
          WHERE id=:id");
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('name', $item['name'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $item['category_id'], PDO::PARAM_INT);
        $statement->bindValue('illustration', $item['illustration'], PDO::PARAM_STR);
        $statement->bindValue('description', $item['description'], PDO::PARAM_STR);
        $statement->bindValue('price', $item['price'], PDO::PARAM_INT);
        $statement->bindValue('stock', $item['stock'], PDO::PARAM_INT);

        return $statement->execute();
    }

    public function searchP(string $keyword): array
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM " . self::TABLE . " 
            WHERE `name` LIKE :keyword 
            OR `description` LIKE :keyword"
        );
        $statement->bindValue('keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
