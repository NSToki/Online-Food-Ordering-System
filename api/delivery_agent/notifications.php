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
require_once "../../models/DeliveryAgentModel.php";

$agentModel = new DeliveryAgentModel($conn);

$profile = $agentModel->getAgentProfile($_SESSION["user_id"]);

if (!$profile || $profile["is_online"] != 1) {

    echo json_encode([
        "success" => true,
        "count" => 0
    ]);

    exit;
}

$orderModel = new OrderModel($conn);

$count = $orderModel->countAvailableOrders($_SESSION["user_id"]);

echo json_encode([
    "success" => true,
    "count" => $count
]);
?>