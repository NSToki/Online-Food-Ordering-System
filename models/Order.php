<?php
class Order {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function placeOrder($customer_id, $restaurant_id, $address,
                               $payment, $subtotal, $fee, $total, $cartItems) {
        $this->conn->begin_transaction();
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO orders (customer_id,restaurant_id,delivery_address,payment_method,
                  subtotal,delivery_fee,total_amount,status,estimated_delivery_minutes)
                 VALUES (?,?,?,?,?,?,?,'pending',30)"
            );
            $stmt->bind_param('iissddd',
                $customer_id,$restaurant_id,$address,$payment,$subtotal,$fee,$total);
            $stmt->execute();
            $order_id = $this->conn->insert_id; $stmt->close();

            $is = $this->conn->prepare(
                "INSERT INTO order_items (order_id,menu_item_id,quantity,unit_price) VALUES (?,?,?,?)"
            );
            foreach ($cartItems as $mid => $item) {
                $is->bind_param('iiid', $order_id, $mid, $item['quantity'], $item['price']);
                $is->execute();
            }
            $is->close();
            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollback(); return false;
        }
    }

    public function getHistory($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, r.name AS restaurant_name FROM orders o
             JOIN restaurants r ON r.id=o.restaurant_id
             WHERE o.customer_id=? ORDER BY o.created_at DESC"
        );
        $stmt->bind_param('i', $customer_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function getById($order_id, $customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT o.*, r.name AS restaurant_name FROM orders o
             JOIN restaurants r ON r.id=o.restaurant_id
             WHERE o.id=? AND o.customer_id=?"
        );
        $stmt->bind_param('ii', $order_id, $customer_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }

    public function getItems($order_id) {
        $stmt = $this->conn->prepare(
            "SELECT oi.*, mi.name AS item_name FROM order_items oi
             JOIN menu_items mi ON mi.id=oi.menu_item_id WHERE oi.order_id=?"
        );
        $stmt->bind_param('i', $order_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    // For AJAX polling — returns status + estimated minutes
    public function getStatus($order_id, $customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT status, estimated_delivery_minutes FROM orders WHERE id=? AND customer_id=?"
        );
        $stmt->bind_param('ii', $order_id, $customer_id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }

    public function cancel($order_id, $customer_id) {
        $stmt = $this->conn->prepare(
            "UPDATE orders SET status='cancelled' WHERE id=? AND customer_id=? AND status='pending'"
        );
        $stmt->bind_param('ii', $order_id, $customer_id); $stmt->execute();
        $aff = $stmt->affected_rows; $stmt->close(); return $aff > 0;
    }

    public function hasReview($order_id) {
        $stmt = $this->conn->prepare("SELECT id FROM reviews WHERE order_id=?");
        $stmt->bind_param('i', $order_id); $stmt->execute(); $stmt->store_result();
        $e = $stmt->num_rows > 0; $stmt->close(); return $e;
    }
}
?>
