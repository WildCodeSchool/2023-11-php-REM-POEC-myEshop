<?php

namespace App\Model;

use PDO;

class CommentManager extends AbstractManager
{
    public const TABLE = 'comment';

    /**
     * Insert new item in database
    */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (content, user_id, product_id, created_at) 
            VALUES (:content, :user, :product, :date )");
        $statement->bindValue(':content', $item['content'], PDO::PARAM_STR);
        $statement->bindValue(':user', $item['user_id'], PDO::PARAM_INT);
        $statement->bindValue(':product', $item['product_id'], PDO::PARAM_INT);
        $statement->bindValue(':date', $item['created_at']);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Get all row from database.
    */
    public function selectAllComments(int $id): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . 'WHERE product_id = :id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Update item in database
    */
    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
