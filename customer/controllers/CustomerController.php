<?php
require_once 'models/Restaurant.php';

class CustomerController {
    private $restaurantModel;

    public function __construct() {
        // Redirect if not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $database = new Database();
        $this->restaurantModel = new Restaurant($database->connect());
    }

    // Show all restaurants
    public function dashboard() {
        $restaurants = $this->restaurantModel->getAllRestaurants();
        require_once 'views/customer/dashboard.php';
    }

    // Show menu for one restaurant
    public function menu() {
        $restaurant_id = intval($_GET['id'] ?? 0);
        $restaurant    = $this->restaurantModel->getRestaurantById($restaurant_id);
        $menu_items    = $this->restaurantModel->getMenuItems($restaurant_id);
        require_once 'views/customer/menu.php';
    }
}
?>
