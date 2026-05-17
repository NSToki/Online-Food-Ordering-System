<?php

class ManagerController {
    private $model;
    private $restaurant;

    public function __construct($model) {
        $this->model = $model;
        if (isManagerLoggedIn()) {
            $this->restaurant = $this->model->getRestaurantByManagerId(getLoggedInUserId());
        }
    }

    private function render($view, $data = []) {
        extract($data);
        $restaurant = $this->restaurant;
        if (in_array($view, ['manager/login', 'manager/register'])) {
            require "views/{$view}.php";
        } else {
            if (!$this->restaurant && $view !== 'manager/profile') {
                $_SESSION['flash_error'] = "Please complete your restaurant profile first.";
                header("Location: ?route=manager/profile");
                exit;
            }
            $contentView = "views/{$view}.php";
            require "views/manager/layout.php";
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->model->login($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                header('Location: ?route=manager/dashboard');
                exit;
            } else {
                $error = "Invalid credentials or inactive account.";
                $this->render('manager/login', ['error' => $error]);
                return;
            }
        }
        $this->render('manager/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            $userId = $this->model->registerManager($name, $email, $password, $phone);
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['role'] = 'manager';
                $_SESSION['name'] = $name;
                header('Location: ?route=manager/profile');
                exit;
            } else {
                $error = "Registration failed. Email might already exist.";
                $this->render('manager/register', ['error' => $error]);
                return;
            }
        }
        $this->render('manager/register');
    }

    public function logout() {
        session_destroy();
        header('Location: ?route=manager/login');
        exit;
    }

    public function dashboard() {
        requireManagerLogin();
        $stats = $this->model->getAnalytics($this->restaurant['id']);
        $this->render('manager/dashboard', ['stats' => $stats]);
    }

    public function profile() {
        requireManagerLogin();
        $managerId = getLoggedInUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    $data['logo_path'] = $targetFile;
                }
            }

            if ($this->restaurant) {
                $this->model->updateRestaurant($managerId, $data);
                $_SESSION['flash_success'] = "Profile updated successfully.";
            } else {
                $this->model->createRestaurant($managerId, $data['name']);
                $this->model->updateRestaurant($managerId, $data);
                $_SESSION['flash_success'] = "Restaurant created successfully.";
            }
            
            header('Location: ?route=manager/profile');
            exit;
        }
        
        $this->render('manager/profile');
    }

    public function menu() {
        requireManagerLogin();
        $restaurantId = $this->restaurant['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add_category') {
                $this->model->addCategory($restaurantId, $_POST['name'], (int)$_POST['display_order']);
            } elseif ($action === 'edit_category') {
                $this->model->updateCategory($_POST['category_id'], $restaurantId, $_POST['name'], (int)$_POST['display_order']);
            } elseif ($action === 'delete_category') {
                $this->model->deleteCategory($_POST['category_id'], $restaurantId);
            } elseif ($action === 'add_item') {
                $data = $_POST;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/menu/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $data['image_path'] = $targetFile;
                    }
                }
                $this->model->addItem($restaurantId, $data);
            } elseif ($action === 'edit_item') {
                $data = $_POST;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/menu/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $data['image_path'] = $targetFile;
                    }
                }
                $this->model->updateItem($_POST['item_id'], $restaurantId, $data);
            } elseif ($action === 'delete_item') {
                $this->model->deleteItem($_POST['item_id'], $restaurantId);
            }
            
            header('Location: ?route=manager/menu');
            exit;
        }

        $categories = $this->model->getCategories($restaurantId);
        $items = $this->model->getItems($restaurantId);
        
        $this->render('manager/menu', [
            'categories' => $categories,
            'items' => $items
        ]);
    }

    public function discounts() {
        requireManagerLogin();
        $restaurantId = $this->restaurant['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $this->model->addDiscount($restaurantId, $_POST['menu_item_id'], $_POST['discount_pct'], $_POST['valid_from'], $_POST['valid_until'], $isActive);
            } elseif ($action === 'toggle') {
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $this->model->updateDiscount($_POST['discount_id'], $restaurantId, $isActive);
            } elseif ($action === 'delete') {
                $this->model->deleteDiscount($_POST['discount_id'], $restaurantId);
            }
            
            header('Location: ?route=manager/discounts');
            exit;
        }

        $discounts = $this->model->getDiscounts($restaurantId);
        $items = $this->model->getItems($restaurantId);
        
        $this->render('manager/discounts', [
            'discounts' => $discounts,
            'items' => $items
        ]);
    }

    public function orders() {
        requireManagerLogin();
        $restaurantId = $this->restaurant['id'];
        
        $orders = $this->model->getOrdersHistory($restaurantId);
        foreach ($orders as &$order) {
            $order['items'] = $this->model->getOrderItems($order['id']);
        }
        
        $this->render('manager/orders', ['orders' => $orders]);
    }

    public function reviews() {
        requireManagerLogin();
        $restaurantId = $this->restaurant['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->replyToReview($_POST['review_id'], $restaurantId, $_POST['manager_reply']);
            header('Location: ?route=manager/reviews');
            exit;
        }

        $reviews = $this->model->getReviews($restaurantId);
        $this->render('manager/reviews', ['reviews' => $reviews]);
    }

    public function analytics() {
        requireManagerLogin();
        $restaurantId = $this->restaurant['id'];
        $stats = $this->model->getAnalytics($restaurantId);
        $this->render('manager/analytics', ['stats' => $stats]);
    }

    public function complaints() {
        requireManagerLogin();
        $complaints = $this->model->getComplaintsByRestaurantName($this->restaurant['name']);
        $this->render('manager/complaints', ['complaints' => $complaints]);
    }
}
