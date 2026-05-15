<?php
require_once 'config/database.php';
require_once 'models/Restaurant.php';

class CustomerController {
    private $db;
    private $restaurantModel;

    public function __construct() {
        $database              = new Database();
        $this->db              = $database->connect();
        $this->restaurantModel = new Restaurant($this->db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────
    public function dashboard() {
        $this->requireLogin();
        $restaurants = $this->restaurantModel->getAllRestaurants();
        require_once 'views/customer/dashboard.php';
    }

    // ─── Menu (restaurant detail + menu items) ───────────────────────────────
    public function menu() {
        $this->requireLogin();
        $restaurant_id = filter_input(INPUT_GET, 'restaurant_id', FILTER_VALIDATE_INT);
        if (!$restaurant_id) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        $restaurant = $this->restaurantModel->getRestaurantById($restaurant_id);
        if (!$restaurant) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        $menu_items = $this->restaurantModel->getMenuItems($restaurant_id);
        $categories = $this->restaurantModel->getMenuCategories($restaurant_id);

        // Store restaurant_id in session so checkout knows which restaurant
        $_SESSION['cart_restaurant_id'] = $restaurant_id;

        require_once 'views/customer/menu.php';
    }
}
?>
