<?php

require_once '../../config/auth_check.php';
require_once '../../models/AnalyticsModel.php';

$analytics = new AnalyticsModel();

$data = $analytics->getDashboardMetrics();

// Correctly unpack all metrics the Dashboard view expects
$activeRestaurants = $data['activeRestaurants'];
$ordersToday       = $data['ordersToday'];
$totalUsers        = $data['totalUsers'];
$activeAgents      = $data['activeAgents'];
$totalRevenue      = $data['totalRevenue'];

$recentOrders = $analytics->getRecentOrders(10);

require '../../views/admin/Dashboard/Dashboard.php';

?>