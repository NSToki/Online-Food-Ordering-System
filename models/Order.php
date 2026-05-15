<?php
class Order {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function placeOrder($customer_id, $restaurant_id, $address, $payment, $subtotal, $delivery_fee, $total, $cartItems) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                "INSERT INTO orders (customer_id, restaurant_id, delivery_address, payment_method,
                 subtotal, delivery_fee, total_amount, status)
                 VALUES (:cid, :rid, :addr, :pay, :sub, :fee, :tot, 'pending')"
            );
            $stmt->execute([
                ':cid'  => $customer_id,
                ':rid'  => $restaurant_id,
                ':addr' => $address,
                ':pay'  => $payment,
                ':sub'  => $subtotal,
                ':fee'  => $delivery_fee,
                ':tot'  => $total
            ]);
            $order_id = $this->conn->lastInsertId();

            $itemStmt = $this->conn->prepare(
                "INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price)
                 VALUES (:oid, :mid, :qty, :price)"
            );
            foreach ($cartItems as $menu_item_id => $item) {
                $itemStmt->execute([
                    ':oid'   => $order_id,
                    ':mid'   => $menu_item_id,
                    ':qty'   => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getOrderHistory($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, r.name AS restaurant_name
             FROM orders o
             LEFT JOIN restaurants r ON o.restaurant_id = r.id
             WHERE o.customer_id = :cid
             ORDER BY o.created_at DESC"
        );
        $stmt->execute([':cid' => $customer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, m.name AS item_name
             FROM order_items oi
             LEFT JOIN menu_items m ON oi.menu_item_id = m.id
             WHERE oi.order_id = :oid"
        );
        $stmt->execute([':oid' => $order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
