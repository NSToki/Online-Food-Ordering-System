<?php

class ManagerApi {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getRestaurantId() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
            return false;
        }
        $managerId = $_SESSION['user_id'];
        $stmt = $this->conn->prepare("SELECT id FROM restaurants WHERE manager_id = ?");
        $stmt->bind_param("i", $managerId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['id'] : false;
    }

    public function getActiveOrders() {
        $restaurantId = $this->getRestaurantId();
        if (!$restaurantId) {
            return ['error' => 'Unauthorized'];
        }

        $stmt = $this->conn->prepare("
            SELECT o.id, o.status, o.total_amount, o.created_at, u.name as customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.restaurant_id = ?
            AND o.status NOT IN ('delivered', 'cancelled')
            ORDER BY o.created_at ASC
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($orders as &$order) {
            $stmtItems = $this->conn->prepare("
                SELECT oi.quantity, mi.name
                FROM order_items oi
                JOIN menu_items mi ON oi.menu_item_id = mi.id
                WHERE oi.order_id = ?
            ");
            $stmtItems->bind_param("i", $order['id']);
            $stmtItems->execute();
            $order['items'] = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
        }

        return ['success' => true, 'orders' => $orders];
    }

    public function updateOrderStatus($orderId, $status) {
        $restaurantId = $this->getRestaurantId();
        if (!$restaurantId) {
            return ['error' => 'Unauthorized'];
        }

        $validStatuses = ['pending', 'accepted', 'preparing', 'ready', 'picked_up', 'on_the_way', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return ['error' => 'Invalid status'];
        }

        $stmtCheck = $this->conn->prepare("SELECT id FROM orders WHERE id = ? AND restaurant_id = ?");
        $stmtCheck->bind_param("ii", $orderId, $restaurantId);
        $stmtCheck->execute();
        if (!$stmtCheck->get_result()->fetch_assoc()) {
            return ['error' => 'Order not found or unauthorized'];
        }

        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $orderId);
        if ($stmt->execute()) {
            return ['success' => true];
        }
        return ['error' => 'Database error'];
    }
}
