<?php

namespace App\Model;

class OrderManager extends AbstractManager
{
    public const TABLE = 'order';

    public function insert(array $order): int
    {
        $statement = $this->pdo->prepare("INSERT INTO `" . self::TABLE . "` (`user_id`, `created_at`,
         `reference`, `address_id`) 
        VALUES (
            :user_id, :created_at, 
            :reference, :address_id
            )");

        $statement->bindValue('user_id', $order['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('created_at', $order['created_at'], \PDO::PARAM_STR);
        $statement->bindValue('reference', $order['reference'], \PDO::PARAM_STR);
        $statement->bindValue('address_id', $order['address_id'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function selectLastOrder(int $id)
    {
        $statement = $this->pdo->prepare(
            "SELECT o.*, od.*, p.*, a.name as address_name
         FROM `order` o
         JOIN `order_details` od ON o.id = od.order_id
         JOIN `product` p ON od.product_id = p.id
         JOIN `address` a ON o.address_id = a.id
         WHERE o.user_id = :id
         ORDER BY o.created_at DESC
         LIMIT 1"
        );

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }


    public function selectAllOrders(int $id)
    {
        $statement = $this->pdo->prepare(
            "SELECT o.*, od.*, p.*
         FROM `order` o
         JOIN `order_details` od ON o.id = od.order_id
         JOIN `product` p ON od.product_id = p.id
         WHERE o.user_id = :id"
        );

        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
