<?php

class ManagerModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ? AND role = 'manager' AND is_active = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if ($password === $row['password_hash'] || password_verify($password, $row['password_hash'])) {
                return $row;
            }
        }
        return false;
    }

    public function registerManager($name, $email, $password, $phone) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'manager';
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hash, $phone, $role);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function getRestaurantByManagerId($managerId) {
        $stmt = $this->conn->prepare("SELECT * FROM restaurants WHERE manager_id = ?");
        $stmt->bind_param("i", $managerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createRestaurant($managerId, $name) {
        $stmt = $this->conn->prepare("INSERT INTO restaurants (manager_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $managerId, $name);
        return $stmt->execute();
    }

    public function updateRestaurant($managerId, $data) {
        $sql = "UPDATE restaurants SET name = ?, description = ?, cuisine_type = ?, address = ?, city = ?, opening_hours = ?, delivery_radius_km = ?, is_open = ?";
        $types = "ssssssdi";
        $params = [
            $data['name'], $data['description'], $data['cuisine_type'],
            $data['address'], $data['city'], $data['opening_hours'],
            $data['delivery_radius_km'], isset($data['is_open']) ? 1 : 0
        ];
        if (isset($data['logo_path']) && !empty($data['logo_path'])) {
            $sql .= ", logo_path = ?";
            $types .= "s";
            $params[] = $data['logo_path'];
        }
        $sql .= " WHERE manager_id = ?";
        $types .= "i";
        $params[] = $managerId;
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function getCategories($restaurantId) {
        $stmt = $this->conn->prepare("SELECT * FROM menu_categories WHERE restaurant_id = ? ORDER BY display_order ASC");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addCategory($restaurantId, $name, $displayOrder) {
        $stmt = $this->conn->prepare("INSERT INTO menu_categories (restaurant_id, name, display_order) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $restaurantId, $name, $displayOrder);
        return $stmt->execute();
    }

    public function updateCategory($categoryId, $restaurantId, $name, $displayOrder) {
        $stmt = $this->conn->prepare("UPDATE menu_categories SET name = ?, display_order = ? WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("siii", $name, $displayOrder, $categoryId, $restaurantId);
        return $stmt->execute();
    }

    public function deleteCategory($categoryId, $restaurantId) {
        $stmt = $this->conn->prepare("DELETE FROM menu_categories WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("ii", $categoryId, $restaurantId);
        return $stmt->execute();
    }

    public function getItems($restaurantId) {
        $stmt = $this->conn->prepare("
            SELECT mi.*, mc.name as category_name
            FROM menu_items mi
            JOIN menu_categories mc ON mi.category_id = mc.id
            WHERE mi.restaurant_id = ?
            ORDER BY mc.display_order ASC, mi.name ASC
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addItem($restaurantId, $data) {
        $stmt = $this->conn->prepare("
            INSERT INTO menu_items (restaurant_id, category_id, name, description, price, image_path, is_available)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $isAvailable = isset($data['is_available']) ? 1 : 0;
        $stmt->bind_param("iissdsi",
            $restaurantId, $data['category_id'], $data['name'],
            $data['description'], $data['price'], $data['image_path'], $isAvailable
        );
        return $stmt->execute();
    }

    public function updateItem($itemId, $restaurantId, $data) {
        $sql = "UPDATE menu_items SET category_id = ?, name = ?, description = ?, price = ?, is_available = ?";
        $types = "issdi";
        $isAvailable = isset($data['is_available']) ? 1 : 0;
        $params = [$data['category_id'], $data['name'], $data['description'], $data['price'], $isAvailable];
        if (isset($data['image_path']) && !empty($data['image_path'])) {
            $sql .= ", image_path = ?";
            $types .= "s";
            $params[] = $data['image_path'];
        }
        $sql .= " WHERE id = ? AND restaurant_id = ?";
        $types .= "ii";
        $params[] = $itemId;
        $params[] = $restaurantId;
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function deleteItem($itemId, $restaurantId) {
        $stmt = $this->conn->prepare("DELETE FROM menu_items WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("ii", $itemId, $restaurantId);
        return $stmt->execute();
    }

    public function getDiscounts($restaurantId) {
        $stmt = $this->conn->prepare("
            SELECT d.*, mi.name as item_name, mi.price as original_price
            FROM discounts d
            JOIN menu_items mi ON d.menu_item_id = mi.id
            WHERE d.restaurant_id = ?
            ORDER BY d.id DESC
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addDiscount($restaurantId, $menuItemId, $discountPct, $validFrom, $validUntil, $isActive) {
        $stmt = $this->conn->prepare("
            INSERT INTO discounts (restaurant_id, menu_item_id, discount_pct, valid_from, valid_until, is_active)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iidssi", $restaurantId, $menuItemId, $discountPct, $validFrom, $validUntil, $isActive);
        return $stmt->execute();
    }

    public function updateDiscount($discountId, $restaurantId, $isActive) {
        $stmt = $this->conn->prepare("UPDATE discounts SET is_active = ? WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("iii", $isActive, $discountId, $restaurantId);
        return $stmt->execute();
    }

    public function deleteDiscount($discountId, $restaurantId) {
        $stmt = $this->conn->prepare("DELETE FROM discounts WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("ii", $discountId, $restaurantId);
        return $stmt->execute();
    }

    public function getOrdersHistory($restaurantId) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.name as customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.restaurant_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("
            SELECT oi.*, mi.name as item_name
            FROM order_items oi
            JOIN menu_items mi ON oi.menu_item_id = mi.id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getReviews($restaurantId) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.name as customer_name
            FROM reviews r
            JOIN users u ON r.customer_id = u.id
            WHERE r.restaurant_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function replyToReview($reviewId, $restaurantId, $reply) {
        $stmt = $this->conn->prepare("UPDATE reviews SET manager_reply = ? WHERE id = ? AND restaurant_id = ?");
        $stmt->bind_param("sii", $reply, $reviewId, $restaurantId);
        return $stmt->execute();
    }

    public function getAnalytics($restaurantId) {
        $data = [
            'total_orders'    => 0,
            'total_revenue'   => 0,
            'avg_order_value' => 0,
            'top_items'       => []
        ];

        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as cnt, SUM(total_amount) as revenue, AVG(total_amount) as avg_val
            FROM orders
            WHERE restaurant_id = ? AND status IN ('delivered')
        ");
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        $data['total_orders']    = $res['cnt']     ?? 0;
        $data['total_revenue']   = $res['revenue'] ?? 0;
        $data['avg_order_value'] = $res['avg_val'] ?? 0;

        $stmt2 = $this->conn->prepare("
            SELECT mi.name, SUM(oi.quantity) as total_sold
            FROM order_items oi
            JOIN menu_items mi ON oi.menu_item_id = mi.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.restaurant_id = ? AND o.status IN ('delivered')
            GROUP BY mi.id
            ORDER BY total_sold DESC
            LIMIT 5
        ");
        $stmt2->bind_param("i", $restaurantId);
        $stmt2->execute();
        $data['top_items'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function getComplaintsByRestaurantName($restaurantName) {
        if (empty($restaurantName)) return [];
        $searchTerm = "%" . $restaurantName . "%";
        $stmt = $this->conn->prepare("
            SELECT c.*, u.name as submitter_name
            FROM complaints c
            JOIN users u ON c.submitter_id = u.id
            WHERE c.description LIKE ? OR c.subject LIKE ?
            ORDER BY c.created_at DESC
        ");
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
