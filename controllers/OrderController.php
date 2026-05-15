<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'models/Restaurant.php';

class OrderController {
    private $db;
    private $orderModel;
    private $restaurantModel;

    public function __construct() {
        $database              = new Database();
        $this->db              = $database->connect();
        $this->orderModel      = new Order($this->db);
        $this->restaurantModel = new Restaurant($this->db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    // ─── Checkout ─────────────────────────────────────────────────────────────
    public function checkout() {
        $this->requireLogin();

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        $delivery_fee  = 2.99;
        $subtotal      = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $total         = $subtotal + $delivery_fee;
        $restaurant_id = $_SESSION['cart_restaurant_id'] ?? null;
        $restaurant    = $restaurant_id ? $this->restaurantModel->getRestaurantById($restaurant_id) : null;

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $address  = trim($_POST['delivery_address'] ?? '');
            $payment  = trim($_POST['payment_method']   ?? '');
            $allowed_payments = ['cash_on_delivery', 'card', 'mobile_banking'];

            if (empty($address)) {
                $error = 'Delivery address is required.';
            } elseif (!in_array($payment, $allowed_payments)) {
                $error = 'Please select a valid payment method.';
            } elseif (!$restaurant_id) {
                $error = 'Unable to determine restaurant. Please start a new order.';
            } else {
                $order_id = $this->orderModel->placeOrder(
                    $_SESSION['user_id'],
                    $restaurant_id,
                    $address,
                    $payment,
                    round($subtotal, 2),
                    round($delivery_fee, 2),
                    round($total, 2),
                    $cart
                );

                if ($order_id) {
                    $_SESSION['cart'] = [];
                    unset($_SESSION['cart_restaurant_id']);
                    $_SESSION['order_success'] = "Order #$order_id placed successfully! 🎉";
                    header('Location: index.php?action=order_history');
                    exit();
                } else {
                    $error = 'Failed to place order. Please try again.';
                }
            }
        }

        require_once 'views/customer/checkout.php';
    }

    // ─── Order History ────────────────────────────────────────────────────────
    public function history() {
        $this->requireLogin();
        $orders = $this->orderModel->getOrderHistory($_SESSION['user_id']);

        // Attach line items to each order
        foreach ($orders as &$order) {
            $order['items'] = $this->orderModel->getOrderItems($order['id']);
        }
        unset($order);

        require_once 'views/customer/order_history.php';
    }
}
?>
