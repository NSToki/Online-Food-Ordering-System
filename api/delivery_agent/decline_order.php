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
$declined = $orderModel->declineOrder($order_id, $_SESSION["user_id"]);

if ($declined) {
    echo json_encode(["success" => true, "message" => "Assignment declined"]);
} else {
    echo json_encode(["success" => false, "message" => "Could not decline this order"]);
}
?>