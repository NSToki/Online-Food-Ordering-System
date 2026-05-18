<?php

require_once __DIR__ . '/../config/database.php';

class AnalyticsModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    // ---------------- DASHBOARD METRICS ----------------

    public function getDashboardMetrics() {

        $activeRestaurants = $this->db
            ->query("SELECT COUNT(*) FROM restaurants WHERE is_approved = 1 AND is_open = 1")
            ->fetchColumn();

        $ordersToday = $this->db
            ->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")
            ->fetchColumn();

        $totalUsers = $this->db
            ->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")
            ->fetchColumn();

        $activeAgents = $this->db
            ->query("SELECT COUNT(*)
                     FROM delivery_agents da
                     JOIN users u ON da.user_id = u.id
                     WHERE da.is_approved = 1
                       AND u.is_active = 1")
            ->fetchColumn();

        $totalRevenue = $this->db
            ->query("SELECT SUM(total_amount) FROM orders WHERE status = 'delivered'")
            ->fetchColumn();

        if (!$totalRevenue) {
            $totalRevenue = 0;
        }

        return [
            "activeRestaurants" => $activeRestaurants,
            "ordersToday" => $ordersToday,
            "totalUsers" => $totalUsers,
            "activeAgents" => $activeAgents,
            "totalRevenue" => $totalRevenue
        ];
    }

    // ---------------- RECENT ORDERS ----------------

    public function getRecentOrders($limit) {

        $sql = "
            SELECT o.id, o.status, o.total_amount, o.created_at,
                   u.name AS customer_name,
                   r.name AS restaurant_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            JOIN restaurants r ON o.restaurant_id = r.id
            ORDER BY o.created_at DESC
            LIMIT $limit
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    // ---------------- REVENUE METRICS ----------------

    public function getRevenueMetrics() {

        $totalRevenue = $this->db
            ->query("SELECT SUM(total_amount) FROM orders WHERE status = 'delivered'")
            ->fetchColumn();

        if (!$totalRevenue) $totalRevenue = 0;

        $totalCommission = $this->db
            ->query("
                SELECT SUM(o.total_amount * (ps.setting_value / 100))
                FROM orders o
                JOIN platform_settings ps
                WHERE ps.setting_key = 'commission_rate_pct'
                AND o.status = 'delivered'
            ")
            ->fetchColumn();

        if (!$totalCommission) $totalCommission = 0;

        $deliveryFees = $this->db
            ->query("SELECT SUM(delivery_fee) FROM orders WHERE status = 'delivered'")
            ->fetchColumn();

        if (!$deliveryFees) $deliveryFees = 0;

        return [
            "totalRevenue" => $totalRevenue,
            "totalCommission" => $totalCommission,
            "deliveryFees" => $deliveryFees
        ];
    }

    // ---------------- MONTHLY REVENUE ----------------

    public function getMonthlyRevenue() {

        $sql = "
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                   SUM(total_amount) AS revenue,
                   COUNT(id) AS total_orders
            FROM orders
            WHERE status = 'delivered'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    // ---------------- TOP RESTAURANTS ----------------

    public function getTopRestaurants($limit) {

        $sql = "
            SELECT r.name,
                   COUNT(o.id) AS order_count,
                   SUM(o.total_amount) AS revenue
            FROM restaurants r
            JOIN orders o ON r.id = o.restaurant_id
            WHERE o.status = 'delivered'
            GROUP BY r.id
            ORDER BY revenue DESC
            LIMIT $limit
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}

?>