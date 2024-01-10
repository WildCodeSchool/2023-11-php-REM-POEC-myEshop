<?php

namespace App\Model;

use PDO;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    /**
     * Insert new item in database
     */
    public function insert(array $item, string $illustration): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, `category_id`,
         `illustration`, `description`, `price`, `stock`) 
         VALUES 
         (:name,:category_id,:illustration,
         :description,:price,:stock)");
        $statement->bindValue(':name', $item['name'], PDO::PARAM_STR);
        $statement->bindValue(':category_id', $item['category_id'], PDO::PARAM_INT);
        $statement->bindValue(':illustration', $illustration, PDO::PARAM_STR);
        $statement->bindValue(':description', $item['description'], PDO::PARAM_STR);
        $statement->bindValue(':price', $item['price'], PDO::PARAM_INT);
        $statement->bindValue(':stock', $item['stock'], PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    // Get all comment product Id
    public function getAllCommentsProductId(int $id): array|false
    {
        $query = 'SELECT p.name AS "Nom livre", c.content, u.firstname, u.lastname, c.created_at 
            FROM comment c
            JOIN product p ON p.id = c.product_id
            JOIN user u ON u.id = c.user_id
            WHERE c.product_id = :id';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasUserPurchasedProduct(int $userId, int $productId): bool
    {
        $query = 'SELECT COUNT(*) FROM order_details od
                  JOIN `order` o ON od.order_id = o.id
                  WHERE o.user_id = :userId AND od.product_id = :productId';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':productId', $productId, \PDO::PARAM_INT);
        $statement->execute();

        return (bool)$statement->fetchColumn();
    }



    /**
     * Update item in database
     */
    public function update(array $item, string $illustration): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `name` = :name,
         `category_id` = :category_id,
         `illustration` = :illustration,
         `description` = :description,
         `price` = :price, `stock` = :stock
          WHERE id=:id");
        $statement->bindValue(':id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue(':name', $item['name'], PDO::PARAM_STR);
        $statement->bindValue(':category_id', $item['category_id'], PDO::PARAM_INT);
        $statement->bindValue(':illustration', $illustration, PDO::PARAM_STR);
        $statement->bindValue(':description', $item['description'], PDO::PARAM_STR);
        $statement->bindValue(':price', $item['price'], PDO::PARAM_INT);
        $statement->bindValue(':stock', $item['stock'], PDO::PARAM_INT);

        return $statement->execute();
    }


    public function selectAllWithCategory(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = 'SELECT p.*, GROUP_CONCAT(c.name) AS category_names
              FROM ' . static::TABLE . ' p
              LEFT JOIN category c ON p.category_id = c.id
              GROUP BY p.id';

        if ($orderBy) {
            $query .= ' ORDER BY p.' . $orderBy . ' ' . $direction;
        }

        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function selectOneByIdWithCategory(int $id): array|false
    {
        $query = 'SELECT p.*, GROUP_CONCAT(c.name) AS category_names
              FROM ' . static::TABLE . ' p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.id = :id
              GROUP BY p.id';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function searchP(string $keyword): array
    {
        $query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names
              FROM " . self::TABLE . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.`name` LIKE :keyword 
              GROUP BY p.id";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductPrice(int $id): int
    {
        $statement = $this->pdo->prepare("SELECT price FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function getProductId(int $id): int
    {
        $id = $this->selectOneById($id);
        return $id['id'];
    }

    public function selectLastProducts(int $limit = 10): array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
