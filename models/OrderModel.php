<?php

require_once __DIR__ . '/../config/database.php';

class OrderModel {

    private $db;

    public function __construct() {
        $this->db = getDB();
    }


    public function getOrders($status = 'all') {

        $sql = "
            SELECT o.id,
                   o.status,
                   o.total_amount,
                   o.created_at,
                   u.name AS customer_name,
                   r.name AS restaurant_name,
                   a.name AS agent_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            JOIN restaurants r ON o.restaurant_id = r.id
            LEFT JOIN delivery_agents da ON o.agent_id = da.id
            LEFT JOIN users a ON da.user_id = a.id
        ";

        if ($status != 'all') {
            $sql = $sql . " WHERE o.status = '$status'";
        }

        $sql = $sql . " ORDER BY o.created_at DESC";

        $result = $this->db->query($sql);

        return $result->fetchAll();
    }
}

?>