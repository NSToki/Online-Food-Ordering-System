<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

require_once "../../food_ordering_db/connection.php";
require_once "../../models/OrderModel.php";

$order_id = $_POST["order_id"] ?? "";

if (empty($order_id) || !filter_var($order_id, FILTER_VALIDATE_INT)) {
    echo json_encode(["success" => false, "message" => "Invalid order ID"]);
    exit;
}

$orderModel = new OrderModel($conn);
$accepted = $orderModel->acceptOrder($order_id, $_SESSION["user_id"]);

if ($accepted) {
    echo json_encode(["success" => true, "message" => "Assignment accepted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Could not accept. Order may already be assigned."]);
}
?>