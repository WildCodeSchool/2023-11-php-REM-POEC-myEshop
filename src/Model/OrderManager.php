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
            "SELECT o.id as order_id, o.user_id, o.created_at, o.reference, o.address_id,
                od.id as order_details_id, od.quantity, od.price_product, od.total,
                p.id as product_id, p.name as product_name, p.price as product_price,
                a.id as address_id, a.name as address_name, a.firstname, a.lastname,
                a.company, a.address, a.postal, a.city, a.country, a.phone
         FROM `order` o
         JOIN `order_details` od ON o.id = od.order_id
         JOIN `product` p ON od.product_id = p.id
         JOIN `address` a ON o.address_id = a.id
         WHERE o.user_id = :id
         ORDER BY o.created_at DESC
         LIMIT 1"
        );

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);

        try {
            $statement->execute();
            return $statement->fetch();
        } catch (\PDOException $e) {
            echo "PDOException: " . $e->getMessage();
            return null;
        }
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

    public function selectOrderDetails($orderId)
    {
        $statement = $this->pdo->prepare(
            "SELECT od.*, p.*
         FROM `order_details` od
         JOIN `product` p ON od.product_id = p.id
         WHERE od.order_id = :orderId
         "
        );

        $statement->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
