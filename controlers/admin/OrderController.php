<?php

require_once '../../config/auth_check.php';
require_once '../../models/OrderModel.php';

$orderModel = new OrderModel();

// View expects $statusFilter, not $status
$statusFilter = $_GET['status'] ?? 'all';

$orders = $orderModel->getOrders($statusFilter);

require '../../views/admin/orders/list.php';

?>