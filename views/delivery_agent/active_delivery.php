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
$delivery = $orderModel->getActiveDelivery($_SESSION["user_id"]);

$items = [];

if ($delivery) {
    $items = $orderModel->getOrderItems($delivery["order_id"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Active Delivery</title>
    <script src="../../assets/js/delivery_agent_ajax.js"></script>

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
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: #dc3545;
            padding: 8px 14px;
            border-radius: 5px;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .info {
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .status-box {
            margin-top: 20px;
            padding: 12px;
            background: #e9ecef;
            border-radius: 5px;
            font-weight: bold;
        }

        button {
            padding: 10px 14px;
            border: none;
            border-radius: 5px;
            color: white;
            margin-top: 15px;
            margin-right: 8px;
            cursor: pointer;
        }

        .pickup {
            background: #17a2b8;
        }

        .way {
            background: #ffc107;
            color: black;
        }

        .delivered {
            background: #28a745;
        }

        .empty {
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Active Delivery</h2>
    <a href="../../controllers/DeliveryAgentController.php?action=logout">Logout</a>
</div>

<div class="container">

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

    <?php if (!$delivery): ?>

        <div class="empty">
            <h3>No active delivery right now.</h3>
            <p>Accept an assignment first from Available Assignments.</p>
        </div>

    <?php else: ?>

        <h3>Order #<?php echo htmlspecialchars($delivery["order_id"]); ?></h3>

        <div class="info">
            <strong>Restaurant:</strong>
            <?php echo htmlspecialchars($delivery["restaurant_name"]); ?><br>

            <strong>Restaurant Address:</strong>
            <?php echo htmlspecialchars($delivery["restaurant_address"]); ?>,
            <?php echo htmlspecialchars($delivery["restaurant_city"]); ?><br>

            <strong>Customer:</strong>
            <?php echo htmlspecialchars($delivery["customer_name"]); ?><br>

            <strong>Delivery Address:</strong>
            <?php echo htmlspecialchars($delivery["delivery_address"]); ?><br>

            <strong>Payment Method:</strong>
            <?php echo htmlspecialchars($delivery["payment_method"]); ?><br>

            <strong>Total Amount:</strong>
            <?php echo htmlspecialchars($delivery["total_amount"]); ?> Tk<br>

            <strong>Your Earning:</strong>
            <?php echo htmlspecialchars($delivery["delivery_fee"]); ?> Tk
        </div>

        <h3>Order Items</h3>

        <?php if (empty($items)): ?>
            <p>No order items found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>

                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item["name"]); ?></td>
                        <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
                        <td><?php echo htmlspecialchars($item["unit_price"]); ?> Tk</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <div class="status-box">
            Current Delivery Status:
            <span id="deliveryStatusText">
                <?php echo htmlspecialchars($delivery["assignment_status"]); ?>
            </span>
        </div>

        <input type="hidden" id="orderId" value="<?php echo htmlspecialchars($delivery["order_id"]); ?>">

        <?php if ($delivery["assignment_status"] == "assigned"): ?>
            <button class="pickup" onclick="updateDeliveryStatus('picked_up')">Mark as Picked Up</button>
        <?php elseif ($delivery["assignment_status"] == "picked_up"): ?>
            <button class="way" onclick="updateDeliveryStatus('on_the_way')">Mark as On The Way</button>
        <?php elseif ($delivery["assignment_status"] == "on_the_way"): ?>
            <button class="delivered" onclick="updateDeliveryStatus('delivered')">Mark as Delivered</button>
        <?php endif; ?>

    <?php endif; ?>

</div>

</body>
</html>