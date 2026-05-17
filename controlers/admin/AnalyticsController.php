<?php

require_once '../../config/auth_check.php';
require_once '../../models/AnalyticsModel.php';

// Create object

$analytics = new AnalyticsModel();

// Revenue data

$revenue = $analytics->getRevenueMetrics();

$totalRevenue = $revenue['totalRevenue'];
$totalCommission = $revenue['totalCommission'];
$deliveryFees = $revenue['deliveryFees'];

// Monthly revenue

$monthlyRevenue = $analytics->getMonthlyRevenue();

// Top restaurants

$topRestaurants = $analytics->getTopRestaurants(5);

// Load page

require_once '../../views/admin/analytics/revenue.php';

?>