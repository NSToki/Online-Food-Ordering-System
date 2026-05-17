<?php
// AjaxController.php — Handles all AJAX cart requests
// session_start() is already called in index.php before this file is included.

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// ── ADD TO CART ───────────────────────────────────────────────────────────────
if ($action == 'add_cart') {
    $id            = $_POST['item_id'];
    $name          = htmlspecialchars($_POST['item_name']);
    $price         = floatval($_POST['item_price']);
    $restaurant_id = intval($_POST['restaurant_id'] ?? 0);

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart_restaurant_id'] = $restaurant_id;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$id] = ['name' => $name, 'price' => $price, 'quantity' => 1];
    }

    $total = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['status' => 'success', 'total_items' => $total]);
    exit();
}

// ── REMOVE ITEM ───────────────────────────────────────────────────────────────
if ($action == 'remove_cart') {
    $id = $_POST['item_id'];
    unset($_SESSION['cart'][$id]);
    $total = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity'));
    echo json_encode(['status' => 'success', 'total_items' => $total]);
    exit();
}

// ── CLEAR CART ────────────────────────────────────────────────────────────────
if ($action == 'clear_cart') {
    unset($_SESSION['cart']);
    unset($_SESSION['cart_restaurant_id']);
    echo json_encode(['status' => 'success', 'total_items' => 0]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
exit();
?>
