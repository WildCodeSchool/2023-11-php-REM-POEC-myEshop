<?php

namespace App\Model;

class OrderDetailsManager extends AbstractManager
{
    public const TABLE = 'order_details';

    public function insert(array $orderDetails): int
    {
        $this->pdo->beginTransaction();

        try {
            foreach ($orderDetails as $detail) {
                $statement = $this->pdo->prepare("INSERT INTO `" . self::TABLE . "` 
                (`order_id`, `quantity`, 
                `price_product`, `total`, 
                `product_id`) 
                VALUES (
                :order_id, :quantity, 
                :price_product, :total, 
                :product_id
                )");
                $statement->bindValue('order_id', $detail['order_id'], \PDO::PARAM_INT);
                $statement->bindValue('quantity', $detail['quantity'], \PDO::PARAM_INT);
                $statement->bindValue('price_product', $detail['price_product'], \PDO::PARAM_STR);
                $statement->bindValue('total', $detail['total'], \PDO::PARAM_STR);
                $statement->bindValue('product_id', $detail['product_id'], \PDO::PARAM_INT);
                $statement->execute();
            }

            $this->pdo->commit();
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function selectAllOrderDetails(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM `" . self::TABLE . "` WHERE order_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
