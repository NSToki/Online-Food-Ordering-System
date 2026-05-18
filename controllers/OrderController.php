<?php
require_once 'models/Order.php';
require_once 'models/Restaurant.php';
require_once 'models/Address.php';
require_once 'models/Review.php';

class OrderController {

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }
    }

    // ── CART ──────────────────────────────────────────────────────
    public function cart() {
        $this->requireLogin();
        $cart = $_SESSION['cart'] ?? [];
        require_once 'views/customer/cart.php';
    }

    // ── CHECKOUT ──────────────────────────────────────────────────
    public function checkout() {
        $this->requireLogin();
        if (empty($_SESSION['cart'])) {
            header("Location: index.php?action=dashboard"); exit();
        }

        $cart     = $_SESSION['cart'];
        $subtotal = 0;
        foreach ($cart as $item) $subtotal += $item['price'] * $item['quantity'];
        $delivery_fee = 50.00;   // fixed fee (academic)
        $total        = $subtotal + $delivery_fee;

        $db          = getDB();
        $addrModel   = new Address($db);
        $addresses   = $addrModel->getAll($_SESSION['user_id']);

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $addr    = trim($_POST['delivery_address'] ?? '');
            $payment = $_POST['payment_method']        ?? 'Cash';

            if ($addr === '') { $error = "Delivery address is required."; }
            else {
                $rid      = intval($_SESSION['cart_restaurant_id'] ?? 0);
                $orderMdl = new Order($db);
                $order_id = $orderMdl->placeOrder(
                    $_SESSION['user_id'], $rid, $addr, $payment,
                    $subtotal, $delivery_fee, $total, $cart
                );
                if ($order_id) {
                    unset($_SESSION['cart'], $_SESSION['cart_restaurant_id']);
                    header("Location: index.php?action=order_confirm&id=$order_id"); exit();
                } else {
                    $error = "Failed to place order. Please try again.";
                }
            }
        }
        require_once 'views/customer/checkout.php';
    }

    // ── ORDER CONFIRMATION ────────────────────────────────────────
    public function confirm() {
        $this->requireLogin();
        $order_id  = intval($_GET['id'] ?? 0);
        $db        = getDB();
        $model     = new Order($db);
        $order     = $model->getById($order_id, $_SESSION['user_id']);
        if (!$order) { header("Location: index.php?action=order_history"); exit(); }
        $items     = $model->getItems($order_id);
        require_once 'views/customer/order_confirm.php';
    }

    // ── ORDER DETAIL + REVIEW FORM ────────────────────────────────
    public function detail() {
        $this->requireLogin();
        $order_id = intval($_GET['id'] ?? 0);
        $db       = getDB();
        $model    = new Order($db);
        $order    = $model->getById($order_id, $_SESSION['user_id']);
        if (!$order) { header("Location: index.php?action=order_history"); exit(); }
        $items         = $model->getItems($order_id);
        $alreadyReview = $model->hasReview($order_id);
        require_once 'views/customer/order_detail.php';
    }

    // ── ORDER HISTORY ─────────────────────────────────────────────
    public function history() {
        $this->requireLogin();
        $db     = getDB();
        $model  = new Order($db);
        $orders = $model->getHistory($_SESSION['user_id']);
        require_once 'views/customer/order_history.php';
    }

    // ── CANCEL ORDER ──────────────────────────────────────────────
    public function cancel() {
        $this->requireLogin();
        $order_id = intval($_GET['id'] ?? 0);
        $db       = getDB();
        $model    = new Order($db);
        $model->cancel($order_id, $_SESSION['user_id']);
        header("Location: index.php?action=order_history&cancelled=1"); exit();
    }

    // ── RE-ORDER ──────────────────────────────────────────────────
    public function reorder() {
        $this->requireLogin();
        $order_id = intval($_GET['id'] ?? 0);
        $db       = getDB();
        $model    = new Order($db);
        $order    = $model->getById($order_id, $_SESSION['user_id']);
        $items    = $model->getItems($order_id);

        if ($order && $items) {
            // Start fresh cart for this restaurant
            $_SESSION['cart'] = [];
            $_SESSION['cart_restaurant_id'] = $order['restaurant_id'];
            foreach ($items as $item) {
                $mid = $item['menu_item_id'];
                if (isset($_SESSION['cart'][$mid])) {
                    $_SESSION['cart'][$mid]['quantity'] += $item['quantity'];
                } else {
                    $_SESSION['cart'][$mid] = [
                        'name'     => $item['item_name'],
                        'price'    => $item['unit_price'],
                        'quantity' => $item['quantity']
                    ];
                }
            }
        }
        header("Location: index.php?action=cart"); exit();
    }

    // ── SUBMIT REVIEW ─────────────────────────────────────────────
    public function submitReview() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=order_history"); exit();
        }
        $order_id = intval($_POST['order_id'] ?? 0);
        $rating   = intval($_POST['rating']   ?? 0);
        $comment  = trim($_POST['comment']    ?? '');

        $db    = getDB();
        $oMdl  = new Order($db);
        $order = $oMdl->getById($order_id, $_SESSION['user_id']);

        if ($order && $order['status'] === 'delivered' && !$oMdl->hasReview($order_id)
            && $rating >= 1 && $rating <= 5) {
            $rMdl = new Review($db);
            $rMdl->submit($order_id, $_SESSION['user_id'], $order['restaurant_id'], $rating, $comment);
        }
        header("Location: index.php?action=order_detail&id=$order_id&reviewed=1"); exit();
    }
}
?>
