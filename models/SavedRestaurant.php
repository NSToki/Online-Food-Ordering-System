<?php
class SavedRestaurant {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getAll($customer_id) {
        $stmt = $this->conn->prepare(
            "SELECT r.*, sr.id AS saved_id, ROUND(AVG(rv.rating),1) AS avg_rating
             FROM saved_restaurants sr
             JOIN restaurants r ON r.id=sr.restaurant_id
             LEFT JOIN reviews rv ON rv.restaurant_id=r.id
             WHERE sr.customer_id=? GROUP BY r.id ORDER BY sr.created_at DESC"
        );
        $stmt->bind_param('i', $customer_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function isSaved($customer_id, $restaurant_id) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM saved_restaurants WHERE customer_id=? AND restaurant_id=?"
        );
        $stmt->bind_param('ii', $customer_id, $restaurant_id); $stmt->execute();
        $stmt->store_result(); $e = $stmt->num_rows > 0; $stmt->close(); return $e;
    }

    public function toggle($customer_id, $restaurant_id) {
        if ($this->isSaved($customer_id, $restaurant_id)) {
            $stmt = $this->conn->prepare(
                "DELETE FROM saved_restaurants WHERE customer_id=? AND restaurant_id=?"
            );
            $stmt->bind_param('ii', $customer_id, $restaurant_id); $stmt->execute(); $stmt->close();
            return 'removed';
        } else {
            $stmt = $this->conn->prepare(
                "INSERT INTO saved_restaurants (customer_id, restaurant_id) VALUES (?,?)"
            );
            $stmt->bind_param('ii', $customer_id, $restaurant_id); $stmt->execute(); $stmt->close();
            return 'saved';
        }
    }
}
?>
