<?php
// AjaxController — all AJAX calls (XMLHttpRequest) land here
// session_start() already called in index.php

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

require_once 'models/Order.php';
require_once 'models/SavedRestaurant.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── ADD TO CART ───────────────────────────────────────────────────
if ($action === 'add_cart') {
    $id            = intval($_POST['item_id']);
    $name          = htmlspecialchars($_POST['item_name'] ?? '');
    $price         = floatval($_POST['item_price']        ?? 0);
    $restaurant_id = intval($_POST['restaurant_id']       ?? 0);

    // If cart belongs to a different restaurant, clear it first
    if (isset($_SESSION['cart_restaurant_id']) && $_SESSION['cart_restaurant_id'] != $restaurant_id) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart_restaurant_id'] = $restaurant_id;
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'quantity' => 1];
    }
    $total = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['status' => 'success', 'total_items' => $total]);
    exit();
}

// ── REMOVE FROM CART ──────────────────────────────────────────────
if ($action === 'remove_cart') {
    $id = intval($_POST['item_id']);
    unset($_SESSION['cart'][$id]);
    $total = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
    echo json_encode(['status' => 'success', 'total_items' => $total]);
    exit();
}

// ── UPDATE CART QUANTITY ──────────────────────────────────────────
if ($action === 'update_qty') {
    $id  = intval($_POST['item_id']);
    $qty = intval($_POST['qty']);
    if (isset($_SESSION['cart'][$id])) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        }
    }
    $total = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
    // Recalculate subtotal for response
    $subtotal = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) $subtotal += $item['price'] * $item['quantity'];
    echo json_encode(['status' => 'success', 'total_items' => $total, 'subtotal' => number_format($subtotal, 2)]);
    exit();
}

// ── CLEAR CART ────────────────────────────────────────────────────
if ($action === 'clear_cart') {
    unset($_SESSION['cart'], $_SESSION['cart_restaurant_id']);
    echo json_encode(['status' => 'success', 'total_items' => 0]);
    exit();
}

// ── POLL ORDER STATUS (AJAX polling for tracking) ─────────────────
if ($action === 'order_status') {
    $order_id = intval($_GET['order_id'] ?? $_POST['order_id'] ?? 0);
    $db       = getDB();
    $model    = new Order($db);
    $row      = $model->getStatus($order_id, $_SESSION['user_id']);
    if ($row) {
        echo json_encode(['status' => 'success', 'order_status' => $row['status'],
                          'estimated_minutes' => $row['estimated_delivery_minutes']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Order not found']);
    }
    exit();
}

// ── TOGGLE FAVOURITE ──────────────────────────────────────────────
if ($action === 'toggle_save') {
    $restaurant_id = intval($_POST['restaurant_id'] ?? 0);
    $db     = getDB();
    $model  = new SavedRestaurant($db);
    $result = $model->toggle($_SESSION['user_id'], $restaurant_id);
    echo json_encode(['status' => 'success', 'action' => $result]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
exit();
?>
