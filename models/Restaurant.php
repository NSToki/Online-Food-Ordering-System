<?php
class Restaurant {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    // All approved restaurants with optional search/filter
    public function getAll($search = '', $cuisine = '', $city = '') {
        $sql = "SELECT r.*, ROUND(AVG(rv.rating),1) AS avg_rating, COUNT(rv.id) AS review_count
                FROM restaurants r
                LEFT JOIN reviews rv ON rv.restaurant_id = r.id
                WHERE r.is_approved = 1";
        $params = []; $types = '';
        if ($search !== '') {
            $sql .= " AND (r.name LIKE ? OR r.cuisine_type LIKE ? OR r.city LIKE ?)";
            $l = "%$search%"; $params[] = $l; $params[] = $l; $params[] = $l; $types .= 'sss';
        }
        if ($cuisine !== '') { $sql .= " AND r.cuisine_type=?"; $params[] = $cuisine; $types .= 's'; }
        if ($city !== '')    { $sql .= " AND r.city=?";         $params[] = $city;    $types .= 's'; }
        $sql .= " GROUP BY r.id ORDER BY r.name ASC";
        $stmt = $this->conn->prepare($sql);
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function getCuisineTypes() {
        return $this->conn->query(
            "SELECT DISTINCT cuisine_type FROM restaurants WHERE is_approved=1 ORDER BY cuisine_type"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function getCities() {
        return $this->conn->query(
            "SELECT DISTINCT city FROM restaurants WHERE is_approved=1 ORDER BY city"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT r.*, ROUND(AVG(rv.rating),1) AS avg_rating, COUNT(rv.id) AS review_count
             FROM restaurants r LEFT JOIN reviews rv ON rv.restaurant_id=r.id
             WHERE r.id=? AND r.is_approved=1 GROUP BY r.id"
        );
        $stmt->bind_param('i', $id); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close(); return $row;
    }

    public function getCategories($restaurant_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM menu_categories WHERE restaurant_id=? ORDER BY display_order ASC"
        );
        $stmt->bind_param('i', $restaurant_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function getMenuItems($restaurant_id) {
        $stmt = $this->conn->prepare(
            "SELECT mi.*,
                COALESCE(d.discount_pct, 0) AS discount_pct,
                CASE WHEN d.id IS NOT NULL
                     THEN ROUND(mi.price*(1-d.discount_pct/100),2)
                     ELSE mi.price END AS discounted_price
             FROM menu_items mi
             LEFT JOIN discounts d ON d.menu_item_id=mi.id AND d.is_active=1
                AND d.valid_from<=NOW() AND d.valid_until>=NOW()
             WHERE mi.restaurant_id=? AND mi.is_available=1
             ORDER BY mi.category_id ASC, mi.name ASC"
        );
        $stmt->bind_param('i', $restaurant_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }

    public function getReviews($restaurant_id) {
        $stmt = $this->conn->prepare(
            "SELECT rv.*, u.name AS customer_name FROM reviews rv
             JOIN users u ON u.id=rv.customer_id
             WHERE rv.restaurant_id=? ORDER BY rv.created_at DESC"
        );
        $stmt->bind_param('i', $restaurant_id); $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); $stmt->close(); return $rows;
    }
}
?>
