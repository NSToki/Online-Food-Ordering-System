<?php
class Restaurant {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all open & approved restaurants
    public function getAllRestaurants() {
        $stmt = $this->conn->prepare(
            "SELECT * FROM restaurants WHERE is_open = 1 AND is_approved = 1"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one restaurant by ID
    public function getRestaurantById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM restaurants WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get available menu items for a restaurant
    public function getMenuItems($restaurant_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM menu_items WHERE restaurant_id = :id AND is_available = 1"
        );
        $stmt->execute([':id' => $restaurant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
