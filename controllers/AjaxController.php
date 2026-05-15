<?php
session_start();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'add_cart') {
        $id    = filter_input(INPUT_POST, 'item_id',    FILTER_VALIDATE_INT);
        $name  = htmlspecialchars(strip_tags($_POST['item_name']  ?? ''), ENT_QUOTES, 'UTF-8');
        $price = filter_input(INPUT_POST, 'item_price', FILTER_VALIDATE_FLOAT);

        if (!$id || !$name || $price === false) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            exit();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'name'     => $name,
                'price'    => $price,
                'quantity' => 1
            ];
        }

        $total_items = array_sum(array_column($_SESSION['cart'], 'quantity'));
        $cart_total  = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $_SESSION['cart']));

        echo json_encode([
            'status'      => 'success',
            'total_items' => $total_items,
            'cart_total'  => number_format($cart_total, 2)
        ]);
        exit();
    }

    if ($_POST['action'] === 'remove_cart') {
        $id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        $total_items = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
        echo json_encode(['status' => 'success', 'total_items' => $total_items]);
        exit();
    }

    if ($_POST['action'] === 'clear_cart') {
        $_SESSION['cart'] = [];
        echo json_encode(['status' => 'success', 'total_items' => 0]);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
    exit();
}
?>
