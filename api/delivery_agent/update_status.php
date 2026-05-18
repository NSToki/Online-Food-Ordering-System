<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access"
    ]);
    exit;
}

require_once "../../food_ordering_db/connection.php";
require_once "../../models/OrderModel.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
    exit;
}

$order_id = $_POST["order_id"] ?? "";
$new_status = $_POST["status"] ?? "";

$allowed_status = ["picked_up", "on_the_way", "delivered"];

if (empty($order_id) || !filter_var($order_id, FILTER_VALIDATE_INT)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid order ID"
    ]);
    exit;
}

if (!in_array($new_status, $allowed_status)) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid delivery status"
    ]);
    exit;
}

$orderModel = new OrderModel($conn);
$updated = $orderModel->updateDeliveryStatus($order_id, $_SESSION["user_id"], $new_status);

if ($updated) {
    echo json_encode([
        "success" => true,
        "status" => $new_status,
        "message" => "Delivery status updated successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Status update failed. Please follow the correct sequence."
    ]);
}
?>