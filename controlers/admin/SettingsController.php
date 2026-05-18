<?php

require_once '../../config/auth_check.php';
require_once '../../models/SettingsModel.php';

$settingsModel = new SettingsModel();

$message = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $commission = $_POST['commission_rate_pct'] ?? 0;
    $baseFee = $_POST['base_delivery_fee'] ?? 0;
    $feePerKm = $_POST['delivery_fee_per_km'] ?? 0;

    $categories = null;

    if (isset($_POST['cuisine_categories'])) {
        // Textarea sends one category per line
        $raw = trim($_POST['cuisine_categories']);
        $categories = array_filter(array_map('trim', explode("\n", $raw)));
        $categories = array_values($categories);
    }

    try {

        $settingsModel->updateSettings(
            $commission,
            $baseFee,
            $feePerKm,
            $categories
        );

        $message = "Settings updated";

    } catch (Exception $e) {
        $error = "Update failed";
    }
}


$settings = $settingsModel->getSettings();

// convert categories to string
$categoriesStr = '';

if (!empty($settings['cuisine_categories'])) {

    $cats = json_decode($settings['cuisine_categories'], true);

    if (is_array($cats)) {
        // Display one per line to match textarea format
        $categoriesStr = implode("\n", $cats);
    }
}

// load view
require '../../views/admin/settings/index.php';

?>