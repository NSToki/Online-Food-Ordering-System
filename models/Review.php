<?php
class Review {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function submit($order_id, $customer_id, $restaurant_id, $rating, $comment) {
        $stmt = $this->conn->prepare(
            "INSERT INTO reviews (order_id, customer_id, restaurant_id, rating, comment) VALUES (?,?,?,?,?)"
        );
        $stmt->bind_param('iiiis', $order_id, $customer_id, $restaurant_id, $rating, $comment);
        $ok = $stmt->execute(); $stmt->close(); return $ok;
    }

    public function getByCustomer($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT rv.*, r.name AS restaurant_name FROM reviews rv
             JOIN restaurants r ON r.id=rv.restaurant_id
             WHERE rv.customer_id=? ORDER BY rv.created_at DESC"
        );
        $stmt->bind_param('i', $customer_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }
}
?>
