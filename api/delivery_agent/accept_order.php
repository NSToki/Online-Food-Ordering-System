<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    header("Location: ../../views/delivery_agent/login.php");
    exit;
}

require_once "../../food_ordering_db/connection.php";
require_once "../../models/OrderModel.php";

if (!isset($_GET["order_id"]) || empty($_GET["order_id"])) {
    $_SESSION["errorMessage"] = "Invalid order selected";
    header("Location: ../../views/delivery_agent/available_orders.php");
    exit;
}

$order_id = $_GET["order_id"];

if (!filter_var($order_id, FILTER_VALIDATE_INT)) {
    $_SESSION["errorMessage"] = "Invalid order ID";
    header("Location: ../../views/delivery_agent/available_orders.php");
    exit;
}

$orderModel = new OrderModel($conn);

$accepted = $orderModel->acceptOrder($order_id, $_SESSION["user_id"]);

if ($accepted) {
    $_SESSION["successMessage"] = "Assignment accepted successfully";
    header("Location: ../../views/delivery_agent/active_delivery.php");
    exit;
} else {
    $_SESSION["errorMessage"] = "Assignment could not be accepted. It may already be assigned.";
    header("Location: ../../views/delivery_agent/available_orders.php");
    exit;
}
?>