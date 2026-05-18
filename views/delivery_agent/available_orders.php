<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "agent") {
    header("Location: login.php");
    exit;
}

require_once "../../food_ordering_db/connection.php";
require_once "../../models/OrderModel.php";

$orderModel = new OrderModel($conn);
$orders = $orderModel->getAvailableOrders($_SESSION["user_id"]);

$successMessage = $_SESSION["successMessage"] ?? "";
$errorMessage = $_SESSION["errorMessage"] ?? "";

unset($_SESSION["successMessage"]);
unset($_SESSION["errorMessage"]);
?>

<!DOCTYPE html>
<html>
<head>
    <script src="../../assets/js/delivery_agent_ajax.js"></script>
    <title>Available Assignments</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background: #007bff;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .order-card {
            background: white;
            padding: 20px;
            margin-bottom: 18px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.08);
        }

        .order-card h3 {
            margin-top: 0;
            color: #333;
        }

        .order-info {
            line-height: 1.7;
        }

        .success {
            color: green;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            background: #fdecea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .empty {
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            color: #555;
        }

        .btn-row {
            margin-top: 15px;
        }

        .accept-btn {
            background: #28a745;
            color: white;
            padding: 9px 14px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 8px;
            display: inline-block;
        }

        .decline-btn {
            background: #6c757d;
            color: white;
            padding: 9px 14px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Available Delivery Assignments</h2>
    <a href="../../controllers/DeliveryAgentController.php?action=logout">Logout</a>
</div>

<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <?php if (!empty($successMessage)): ?>
        <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
        <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <?php if (empty($orders)): ?>

        <div class="empty">
            <h3>No available assignments right now.</h3>
            <p>Only orders marked as Ready for Pickup and not assigned to any agent will appear here.</p>
        </div>

    <?php else: ?>

        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <h3>Order #<?php echo htmlspecialchars($order["order_id"]); ?></h3>

                <div class="order-info">
                    <strong>Restaurant:</strong>
                    <?php echo htmlspecialchars($order["restaurant_name"]); ?><br>

                    <strong>Restaurant Address:</strong>
                    <?php echo htmlspecialchars($order["restaurant_address"]); ?>,
                    <?php echo htmlspecialchars($order["restaurant_city"]); ?><br>

                    <strong>Customer:</strong>
                    <?php echo htmlspecialchars($order["customer_name"]); ?><br>

                    <strong>Delivery Address:</strong>
                    <?php echo htmlspecialchars($order["delivery_address"]); ?><br>

                    <strong>Payment Method:</strong>
                    <?php echo htmlspecialchars($order["payment_method"]); ?><br>

                    <strong>Delivery Fee:</strong>
                    <?php echo htmlspecialchars($order["delivery_fee"]); ?> Tk<br>

                    <strong>Total Amount:</strong>
                    <?php echo htmlspecialchars($order["total_amount"]); ?> Tk<br>

                    <strong>Estimated Delivery Time:</strong>
                    <?php echo htmlspecialchars($order["estimated_delivery_minutes"]); ?> minutes<br>

                    <strong>Order Created:</strong>
                    <?php echo htmlspecialchars($order["created_at"]); ?>
                </div>

                <div class="btn-row">
                    <button class="accept-btn" onclick="acceptOrder(<?php echo (int)$order['order_id']; ?>)">
                    Accept Assignment
                    </button>

                    <button class="decline-btn" onclick="declineOrder(<?php echo (int)$order['order_id']; ?>)">
                    Decline
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>