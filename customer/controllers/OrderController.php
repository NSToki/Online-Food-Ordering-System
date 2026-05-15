<?php
require_once 'models/Order.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $database = new Database();
        $this->orderModel = new Order($database->connect());
    }

    // Show cart page
    public function cart() {
        $cart = $_SESSION['cart'] ?? [];
        require_once 'views/customer/cart.php';
    }

    // Show checkout form & process order
    public function checkout() {
        // Empty cart? Go back to dashboard
        if (empty($_SESSION['cart'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $cart = $_SESSION['cart'];

        // Calculate totals
        $subtotal     = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $delivery_fee = 5.00;
        $total        = $subtotal + $delivery_fee;

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address    = htmlspecialchars(trim($_POST['delivery_address']));
            $payment    = $_POST['payment_method'];
            $restaurant_id = intval($_SESSION['cart_restaurant_id'] ?? 1);

            if ($this->orderModel->placeOrder(
                $_SESSION['user_id'], $restaurant_id, $address, $payment,
                $subtotal, $delivery_fee, $total, $cart
            )) {
                unset($_SESSION['cart']);
                unset($_SESSION['cart_restaurant_id']);
                header("Location: index.php?action=order_history&success=1");
                exit();
            } else {
                $error = "Failed to place order. Please try again.";
            }
        }

        require_once 'views/customer/checkout.php';
    }

    // Show order history
    public function history() {
        $orders = $this->orderModel->getOrderHistory($_SESSION['user_id']);
        require_once 'views/customer/order_history.php';
    }
}
?>
