CREATE DATABASE online_food_ordering_system;
USE online_food_ordering_system;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('customer', 'manager', 'agent', 'admin') NOT NULL,
    profile_pic VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- RESTAURANTS TABLE
CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    manager_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    cuisine_type VARCHAR(100),
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    logo_path VARCHAR(255),
    opening_hours VARCHAR(100),
    delivery_radius_km DECIMAL(5,2) DEFAULT 5.00,
    is_open TINYINT(1) DEFAULT 1,
    is_approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (manager_id) REFERENCES users(id)
);

-- MENU CATEGORIES TABLE
CREATE TABLE menu_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    display_order INT DEFAULT 0,

    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- MENU ITEMS TABLE
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    category_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    is_available TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id),
    FOREIGN KEY (category_id) REFERENCES menu_categories(id)
);

-- DISCOUNTS TABLE
CREATE TABLE discounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_item_id INT NOT NULL,
    restaurant_id INT NOT NULL,
    discount_pct DECIMAL(5,2) NOT NULL,
    valid_from DATETIME NOT NULL,
    valid_until DATETIME NOT NULL,
    is_active TINYINT(1) DEFAULT 1,

    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- DELIVERY AGENTS TABLE
CREATE TABLE delivery_agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    vehicle_type VARCHAR(50) NOT NULL,
    is_online TINYINT(1) DEFAULT 0,
    current_location_text VARCHAR(255),
    total_earnings DECIMAL(10,2) DEFAULT 0.00,
    is_approved TINYINT(1) DEFAULT 0,

    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ORDERS TABLE
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    restaurant_id INT NOT NULL,
    agent_id INT DEFAULT NULL,
    delivery_address TEXT NOT NULL,
    payment_method ENUM('Cash', 'Card') NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    delivery_fee DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,

    status ENUM(
        'pending',
        'accepted',
        'preparing',
        'ready',
        'picked_up',
        'on_the_way',
        'delivered',
        'cancelled'
    ) DEFAULT 'pending',

    estimated_delivery_minutes INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id),
    FOREIGN KEY (agent_id) REFERENCES delivery_agents(id)
);

-- ORDER ITEMS TABLE
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- DELIVERY ASSIGNMENTS TABLE
CREATE TABLE delivery_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    agent_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    picked_up_at DATETIME DEFAULT NULL,
    delivered_at DATETIME DEFAULT NULL,

    status ENUM(
        'assigned',
        'declined',
        'picked_up',
        'on_the_way',
        'delivered'
    ) DEFAULT 'assigned',

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (agent_id) REFERENCES delivery_agents(id)
);

-- REVIEWS TABLE
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    restaurant_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    manager_reply TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- SAVED RESTAURANTS TABLE
CREATE TABLE saved_restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    restaurant_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id),

    UNIQUE(customer_id, restaurant_id)
);

-- DELIVERY ADDRESSES TABLE
CREATE TABLE delivery_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    label VARCHAR(50) NOT NULL,
    address_line TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    is_default TINYINT(1) DEFAULT 0,

    FOREIGN KEY (customer_id) REFERENCES users(id)
);

-- COMPLAINTS TABLE
CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submitter_id INT NOT NULL,
    subject VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,

    status ENUM('open', 'resolved') DEFAULT 'open',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (submitter_id) REFERENCES users(id)
);

-- PLATFORM SETTINGS TABLE

CREATE TABLE platform_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value VARCHAR(255) NOT NULL
);