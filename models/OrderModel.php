<?php

class OrderModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAvailableOrders($user_id) {

            $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";
            $agentStmt = mysqli_prepare($this->conn, $agentSql);
            mysqli_stmt_bind_param($agentStmt, "i", $user_id);
            mysqli_stmt_execute($agentStmt);

            $agentResult = mysqli_stmt_get_result($agentStmt);
            $agent = mysqli_fetch_assoc($agentResult);

            mysqli_stmt_close($agentStmt);

            if (!$agent) {
                return [];
            }

            $agent_id = $agent["id"];

            $sql = "SELECT 
                        orders.id AS order_id,
                        orders.delivery_address,
                        orders.payment_method,
                        orders.total_amount,
                        orders.delivery_fee,
                        orders.estimated_delivery_minutes,
                        orders.created_at,
                        restaurants.name AS restaurant_name,
                        restaurants.address AS restaurant_address,
                        restaurants.city AS restaurant_city,
                        users.name AS customer_name
                    FROM orders
                    INNER JOIN restaurants ON orders.restaurant_id = restaurants.id
                    INNER JOIN users ON orders.customer_id = users.id

                    WHERE orders.status = 'ready'
                    AND orders.agent_id IS NULL

                    AND orders.id NOT IN (
                        SELECT order_id 
                        FROM declined_assignments
                        WHERE agent_id = ?
                    )

                    ORDER BY orders.created_at ASC";

            $stmt = mysqli_prepare($this->conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $agent_id);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $orders = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $orders[] = $row;
            }

            mysqli_stmt_close($stmt);

            return $orders;
}

    public function acceptOrder($order_id, $user_id) {
    mysqli_begin_transaction($this->conn);

    try {
        $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";
        $agentStmt = mysqli_prepare($this->conn, $agentSql);
        mysqli_stmt_bind_param($agentStmt, "i", $user_id);
        mysqli_stmt_execute($agentStmt);

        $agentResult = mysqli_stmt_get_result($agentStmt);
        $agent = mysqli_fetch_assoc($agentResult);
        mysqli_stmt_close($agentStmt);

        if (!$agent) {
            mysqli_rollback($this->conn);
            return false;
        }

        $agent_id = $agent["id"];

        $checkSql = "SELECT id FROM orders 
                     WHERE id = ? 
                     AND status = 'ready' 
                     AND agent_id IS NULL";

        $checkStmt = mysqli_prepare($this->conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "i", $order_id);
        mysqli_stmt_execute($checkStmt);

        $checkResult = mysqli_stmt_get_result($checkStmt);
        $order = mysqli_fetch_assoc($checkResult);
        mysqli_stmt_close($checkStmt);

        if (!$order) {
            mysqli_rollback($this->conn);
            return false;
        }

        $updateSql = "UPDATE orders 
                      SET agent_id = ?, status = 'accepted' 
                      WHERE id = ?";

        $updateStmt = mysqli_prepare($this->conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ii", $agent_id, $order_id);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);

        $assignSql = "INSERT INTO delivery_assignments 
                      (order_id, agent_id, assigned_at, status)
                      VALUES (?, ?, NOW(), 'assigned')";

        $assignStmt = mysqli_prepare($this->conn, $assignSql);
        mysqli_stmt_bind_param($assignStmt, "ii", $order_id, $agent_id);
        mysqli_stmt_execute($assignStmt);
        mysqli_stmt_close($assignStmt);

        mysqli_commit($this->conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($this->conn);
        return false;
    }
    }
    public function getActiveDelivery($user_id) {
    $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";
    $agentStmt = mysqli_prepare($this->conn, $agentSql);
    mysqli_stmt_bind_param($agentStmt, "i", $user_id);
    mysqli_stmt_execute($agentStmt);

    $agentResult = mysqli_stmt_get_result($agentStmt);
    $agent = mysqli_fetch_assoc($agentResult);
    mysqli_stmt_close($agentStmt);

    if (!$agent) {
        return null;
    }

    $agent_id = $agent["id"];

    $sql = "SELECT 
                orders.id AS order_id,
                orders.delivery_address,
                orders.payment_method,
                orders.total_amount,
                orders.delivery_fee,
                orders.estimated_delivery_minutes,
                orders.status AS order_status,
                restaurants.name AS restaurant_name,
                restaurants.address AS restaurant_address,
                restaurants.city AS restaurant_city,
                users.name AS customer_name,
                delivery_assignments.status AS assignment_status
            FROM orders
            INNER JOIN restaurants ON orders.restaurant_id = restaurants.id
            INNER JOIN users ON orders.customer_id = users.id
            INNER JOIN delivery_assignments ON orders.id = delivery_assignments.order_id
            WHERE orders.agent_id = ?
            AND delivery_assignments.agent_id = ?
            AND delivery_assignments.status != 'delivered'
            ORDER BY delivery_assignments.assigned_at DESC
            LIMIT 1";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $agent_id, $agent_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $delivery = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $delivery;
}

public function getOrderItems($order_id) {
    $sql = "SELECT 
                menu_items.name,
                order_items.quantity,
                order_items.unit_price
            FROM order_items
            INNER JOIN menu_items ON order_items.menu_item_id = menu_items.id
            WHERE order_items.order_id = ?";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $items = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $items;
}

public function updateDeliveryStatus($order_id, $user_id, $new_status) {
    mysqli_begin_transaction($this->conn);

    try {
        $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";
        $agentStmt = mysqli_prepare($this->conn, $agentSql);
        mysqli_stmt_bind_param($agentStmt, "i", $user_id);
        mysqli_stmt_execute($agentStmt);

        $agentResult = mysqli_stmt_get_result($agentStmt);
        $agent = mysqli_fetch_assoc($agentResult);
        mysqli_stmt_close($agentStmt);

        if (!$agent) {
            mysqli_rollback($this->conn);
            return false;
        }

        $agent_id = $agent["id"];

        if ($new_status == "picked_up") {
            $order_status = "picked_up";

            $sql = "UPDATE delivery_assignments 
                    SET status = ?, picked_up_at = NOW()
                    WHERE order_id = ? AND agent_id = ? AND status = 'assigned'";
        } elseif ($new_status == "on_the_way") {
            $order_status = "picked_up";

            $sql = "UPDATE delivery_assignments 
                    SET status = ?
                    WHERE order_id = ? AND agent_id = ? AND status = 'picked_up'";
        } elseif ($new_status == "delivered") {
            $order_status = "delivered";

            $sql = "UPDATE delivery_assignments 
                    SET status = ?, delivered_at = NOW()
                    WHERE order_id = ? AND agent_id = ? AND status = 'on_the_way'";
        } else {
            mysqli_rollback($this->conn);
            return false;
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $new_status, $order_id, $agent_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) < 1) {
            mysqli_stmt_close($stmt);
            mysqli_rollback($this->conn);
            return false;
        }

        mysqli_stmt_close($stmt);

        $orderSql = "UPDATE orders SET status = ? WHERE id = ? AND agent_id = ?";
        $orderStmt = mysqli_prepare($this->conn, $orderSql);
        mysqli_stmt_bind_param($orderStmt, "sii", $order_status, $order_id, $agent_id);
        mysqli_stmt_execute($orderStmt);
        mysqli_stmt_close($orderStmt);

        if ($new_status == "delivered") {
            $earningSql = "UPDATE delivery_agents 
                           SET total_earnings = total_earnings + 
                           (SELECT delivery_fee FROM orders WHERE id = ?)
                           WHERE id = ?";

            $earningStmt = mysqli_prepare($this->conn, $earningSql);
            mysqli_stmt_bind_param($earningStmt, "ii", $order_id, $agent_id);
            mysqli_stmt_execute($earningStmt);
            mysqli_stmt_close($earningStmt);
        }

        mysqli_commit($this->conn);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($this->conn);
        return false;
    }
    }
    public function declineOrder($order_id, $user_id) {

        $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";
        $agentStmt = mysqli_prepare($this->conn, $agentSql);

        mysqli_stmt_bind_param($agentStmt, "i", $user_id);

        mysqli_stmt_execute($agentStmt);

        $agentResult = mysqli_stmt_get_result($agentStmt);

        $agent = mysqli_fetch_assoc($agentResult);

        mysqli_stmt_close($agentStmt);

        if (!$agent) {
            return false;
        }

        $agent_id = $agent["id"];

        $checkSql = "SELECT id FROM orders
                    WHERE id = ?
                    AND status = 'ready'
                    AND agent_id IS NULL";

        $checkStmt = mysqli_prepare($this->conn, $checkSql);

        mysqli_stmt_bind_param($checkStmt, "i", $order_id);

        mysqli_stmt_execute($checkStmt);

        $checkResult = mysqli_stmt_get_result($checkStmt);

        $order = mysqli_fetch_assoc($checkResult);

        mysqli_stmt_close($checkStmt);

        if (!$order) {
            return false;
        }

        $insertSql = "INSERT INTO declined_assignments
                    (order_id, agent_id)
                    VALUES (?, ?)";

        $insertStmt = mysqli_prepare($this->conn, $insertSql);

        mysqli_stmt_bind_param($insertStmt, "ii", $order_id, $agent_id);

        $success = mysqli_stmt_execute($insertStmt);

        mysqli_stmt_close($insertStmt);

        return $success;
    }
    public function getDeliveryHistory($user_id) {

    $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";

    $agentStmt = mysqli_prepare($this->conn, $agentSql);

    mysqli_stmt_bind_param($agentStmt, "i", $user_id);

    mysqli_stmt_execute($agentStmt);

    $agentResult = mysqli_stmt_get_result($agentStmt);

    $agent = mysqli_fetch_assoc($agentResult);

    mysqli_stmt_close($agentStmt);

    if (!$agent) {
        return [];
    }

    $agent_id = $agent["id"];

    $sql = "SELECT
                orders.id AS order_id,
                orders.delivery_address,
                orders.delivery_fee,
                orders.total_amount,
                orders.status,
                orders.created_at,

                restaurants.name AS restaurant_name,
                restaurants.city AS restaurant_city,

                users.name AS customer_name,

                delivery_assignments.assigned_at,
                delivery_assignments.picked_up_at,
                delivery_assignments.delivered_at

            FROM orders

            INNER JOIN restaurants
            ON orders.restaurant_id = restaurants.id

            INNER JOIN users
            ON orders.customer_id = users.id

            INNER JOIN delivery_assignments
            ON orders.id = delivery_assignments.order_id

            WHERE delivery_assignments.agent_id = ?
            AND delivery_assignments.status = 'delivered'

            ORDER BY delivery_assignments.delivered_at DESC";

    $stmt = mysqli_prepare($this->conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $agent_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $history = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $history[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $history;
    }
    public function countAvailableOrders($user_id) {

    $agentSql = "SELECT id FROM delivery_agents WHERE user_id = ?";

    $agentStmt = mysqli_prepare($this->conn, $agentSql);

    mysqli_stmt_bind_param($agentStmt, "i", $user_id);

    mysqli_stmt_execute($agentStmt);

    $agentResult = mysqli_stmt_get_result($agentStmt);

    $agent = mysqli_fetch_assoc($agentResult);

    mysqli_stmt_close($agentStmt);

    if (!$agent) {
        return 0;
    }

    $agent_id = $agent["id"];

    $sql = "SELECT COUNT(orders.id) AS total_available

            FROM orders

            WHERE orders.status = 'ready'
            AND orders.agent_id IS NULL

            AND orders.id NOT IN (
                SELECT order_id
                FROM declined_assignments
                WHERE agent_id = ?
            )";

    $stmt = mysqli_prepare($this->conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $agent_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $data["total_available"] ?? 0;
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