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

$history = $orderModel->getDeliveryHistory($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delivery History</title>

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
            width: 95%;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .empty {
            text-align: center;
            color: #555;
            padding: 30px;
        }

    </style>

</head>

<body>

<div class="navbar">
    <h2>Delivery History</h2>

    <a href="../../controllers/DeliveryAgentController.php?action=logout">
        Logout
    </a>
</div>

<div class="container">

    <a class="back" href="dashboard.php">
        ← Back to Dashboard
    </a>

    <?php if (empty($history)): ?>

        <div class="empty">

            <h3>No completed deliveries yet.</h3>

            <p>
                Your delivered assignments will appear here.
            </p>

        </div>

    <?php else: ?>

        <table>

            <tr>
                <th>Order ID</th>
                <th>Restaurant</th>
                <th>Customer</th>
                <th>Customer Area</th>
                <th>Delivery Fee</th>
                <th>Total Amount</th>
                <th>Assigned At</th>
                <th>Delivered At</th>
                <th>Status</th>
            </tr>

            <?php foreach ($history as $delivery): ?>

                <tr>

                    <td>
                        #<?php echo htmlspecialchars($delivery["order_id"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["restaurant_name"]); ?>
                        <br>
                        <small>
                            <?php echo htmlspecialchars($delivery["restaurant_city"]); ?>
                        </small>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["customer_name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["delivery_address"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["delivery_fee"]); ?> Tk
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["total_amount"]); ?> Tk
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["assigned_at"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["delivered_at"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($delivery["status"]); ?>
                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</div>

</body>

</html>