<?php

namespace App\Model;

use PDO;

class CategoryManager extends AbstractManager
{
    public const TABLE = 'category';

    public function insert(array $category): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(name, description)
         VALUES (:name, :description)");
        $statement->bindValue(':name', $category['name'], PDO::PARAM_STR);
        $statement->bindValue(':description', $category['description'], PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $category): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name=:name, 
        description=:description 
        WHERE id=:id");
        $statement->bindValue(':name', $category['name'], PDO::PARAM_STR);
        $statement->bindValue(':description', $category['description'], PDO::PARAM_STR);
        $statement->bindValue(':id', $category['id'], PDO::PARAM_INT);
        return $statement->execute();
    }

    public function searchC(string $keyword): array
    {
        $statement = $this->pdo->prepare(
            "SELECT * FROM " . self::TABLE . " 
            WHERE `name` LIKE :keyword 
            OR `description` LIKE :keyword OR `id` LIKE :keyword"
        );
        $statement->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}
