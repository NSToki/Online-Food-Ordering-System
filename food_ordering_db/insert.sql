USE online_food_ordering_system;

-- =========================================
-- USERS TABLE DATA
-- =========================================
INSERT INTO users (name, email, password_hash, phone, role, profile_pic, is_active)
VALUES
('Admin User', 'admin@gmail.com', '$2y$10$admin123hash', '01710000001', 'admin', 'admin.jpg', 1),
('Rahim Customer', 'customer1@gmail.com', '$2y$10$customer123hash', '01710000002', 'customer', 'customer1.jpg', 1),
('Karim Customer', 'customer2@gmail.com', '$2y$10$customer456hash', '01710000003', 'customer', 'customer2.jpg', 1),
('Burger Manager', 'manager@gmail.com', '$2y$10$manager123hash', '01710000004', 'manager', 'manager.jpg', 1),
('Jihad Agent', 'agent@gmail.com', '$2y$10$agent123hash', '01710000005', 'agent', 'agent.jpg', 1);

-- =========================================
-- RESTAURANTS TABLE DATA
-- =========================================
INSERT INTO restaurants (
    manager_id,
    name,
    description,
    cuisine_type,
    address,
    city,
    logo_path,
    opening_hours,
    delivery_radius_km,
    is_open,
    is_approved
)
VALUES
(4, 'Burger House', 'Best burgers in town', 'Fast Food', 'Dhanmondi 10', 'Dhaka', 'burgerhouse.png', '10AM-11PM', 5.00, 1, 1),
(4, 'Pizza Point', 'Delicious pizza items', 'Italian', 'Mirpur 1', 'Dhaka', 'pizza.png', '9AM-10PM', 6.00, 1, 1),
(4, 'Spicy Grill', 'Grill and BBQ items', 'BBQ', 'Uttara Sector 7', 'Dhaka', 'grill.png', '11AM-12AM', 7.00, 1, 1),
(4, 'Food Corner', 'Chinese and Thai foods', 'Chinese', 'Banani 11', 'Dhaka', 'foodcorner.png', '10AM-9PM', 4.00, 1, 1),
(4, 'Cafe Time', 'Coffee and snacks', 'Cafe', 'Mohakhali', 'Dhaka', 'cafe.png', '8AM-11PM', 3.00, 1, 1);

-- =========================================
-- MENU CATEGORIES TABLE DATA
-- =========================================
INSERT INTO menu_categories (restaurant_id, name, display_order)
VALUES
(1, 'Burgers', 1),
(1, 'Drinks', 2),
(2, 'Pizza', 1),
(3, 'BBQ', 1),
(4, 'Chinese', 1);

-- =========================================
-- MENU ITEMS TABLE DATA
-- =========================================
INSERT INTO menu_items (
    restaurant_id,
    category_id,
    name,
    description,
    price,
    image_path,
    is_available
)
VALUES
(1, 1, 'Cheese Burger', 'Double cheese burger', 250.00, 'burger1.jpg', 1),
(1, 2, 'Coke', 'Cold drinks', 40.00, 'coke.jpg', 1),
(2, 3, 'Chicken Pizza', 'Medium chicken pizza', 550.00, 'pizza1.jpg', 1),
(3, 4, 'BBQ Chicken', 'Spicy BBQ chicken', 450.00, 'bbq.jpg', 1),
(4, 5, 'Fried Rice', 'Special fried rice', 300.00, 'friedrice.jpg', 1);

-- =========================================
-- DISCOUNTS TABLE DATA
-- =========================================
INSERT INTO discounts (
    menu_item_id,
    restaurant_id,
    discount_pct,
    valid_from,
    valid_until,
    is_active
)
VALUES
(1, 1, 10.00, '2026-05-01 00:00:00', '2026-05-31 23:59:59', 1),
(2, 1, 5.00, '2026-05-01 00:00:00', '2026-05-31 23:59:59', 1),
(3, 2, 15.00, '2026-05-01 00:00:00', '2026-05-31 23:59:59', 1),
(4, 3, 20.00, '2026-05-01 00:00:00', '2026-05-31 23:59:59', 1),
(5, 4, 8.00, '2026-05-01 00:00:00', '2026-05-31 23:59:59', 1);

-- =========================================
-- DELIVERY AGENTS TABLE DATA
-- =========================================
INSERT INTO delivery_agents (
    user_id,
    vehicle_type,
    is_online,
    current_location_text,
    total_earnings,
    is_approved
)
VALUES
(5, 'Bike', 1, 'Dhanmondi', 2500.00, 1),
(5, 'Bike', 0, 'Mirpur', 3000.00, 1),
(5, 'Scooter', 1, 'Uttara', 1500.00, 1),
(5, 'Cycle', 1, 'Banani', 1200.00, 1),
(5, 'Bike', 0, 'Mohakhali', 1800.00, 1);

-- =========================================
-- ORDERS TABLE DATA
-- =========================================
INSERT INTO orders (
    customer_id,
    restaurant_id,
    agent_id,
    delivery_address,
    payment_method,
    subtotal,
    delivery_fee,
    total_amount,
    status,
    estimated_delivery_minutes
)
VALUES
(2, 1, 1, 'Dhanmondi 15', 'Cash', 250.00, 50.00, 300.00, 'pending', 30),
(3, 2, 1, 'Mirpur 2', 'Card', 550.00, 60.00, 610.00, 'accepted', 40),
(2, 3, 1, 'Uttara 5', 'Cash', 450.00, 70.00, 520.00, 'preparing', 35),
(3, 4, 1, 'Banani 10', 'Card', 300.00, 50.00, 350.00, 'ready', 25),
(2, 1, 1, 'Mohakhali DOHS', 'Cash', 290.00, 40.00, 330.00, 'delivered', 20);

-- =========================================
-- ORDER ITEMS TABLE DATA
-- =========================================
INSERT INTO order_items (
    order_id,
    menu_item_id,
    quantity,
    unit_price
)
VALUES
(1, 1, 1, 250.00),
(2, 3, 1, 550.00),
(3, 4, 1, 450.00),
(4, 5, 1, 300.00),
(5, 2, 2, 40.00);

-- =========================================
-- DELIVERY ASSIGNMENTS TABLE DATA
-- =========================================
INSERT INTO delivery_assignments (
    order_id,
    agent_id,
    assigned_at,
    picked_up_at,
    delivered_at,
    status
)
VALUES
(1, 1, NOW(), NULL, NULL, 'assigned'),
(2, 1, NOW(), NULL, NULL, 'assigned'),
(3, 1, NOW(), NOW(), NULL, 'picked_up'),
(4, 1, NOW(), NOW(), NULL, 'on_the_way'),
(5, 1, NOW(), NOW(), NOW(), 'delivered');

-- =========================================
-- REVIEWS TABLE DATA
-- =========================================
INSERT INTO reviews (
    order_id,
    customer_id,
    restaurant_id,
    rating,
    comment,
    manager_reply
)
VALUES
(5, 2, 1, 5, 'Excellent food', 'Thank you'),
(2, 3, 2, 4, 'Good pizza', 'Thanks for your review'),
(3, 2, 3, 3, 'Average taste', 'We will improve'),
(4, 3, 4, 5, 'Very tasty food', 'Glad you liked it'),
(1, 2, 1, 4, 'Fast delivery', 'Thank you so much');

-- =========================================
-- SAVED RESTAURANTS TABLE DATA
-- =========================================
INSERT INTO saved_restaurants (
    customer_id,
    restaurant_id
)
VALUES
(2, 1),
(2, 2),
(3, 3),
(3, 4),
(2, 5);

-- =========================================
-- DELIVERY ADDRESSES TABLE DATA
-- =========================================
INSERT INTO delivery_addresses (
    customer_id,
    label,
    address_line,
    city,
    is_default
)
VALUES
(2, 'Home', 'Dhanmondi 15', 'Dhaka', 1),
(2, 'Office', 'Motijheel', 'Dhaka', 0),
(3, 'Home', 'Mirpur 2', 'Dhaka', 1),
(3, 'Friend House', 'Banani 10', 'Dhaka', 0),
(2, 'Village Home', 'Gazipur', 'Dhaka', 0);

-- =========================================
-- COMPLAINTS TABLE DATA
-- =========================================
INSERT INTO complaints (
    submitter_id,
    subject,
    description,
    status
)
VALUES
(2, 'Late Delivery', 'Order arrived very late', 'open'),
(3, 'Cold Food', 'Food was cold', 'resolved'),
(2, 'Wrong Item', 'Received wrong burger', 'open'),
(5, 'Customer Issue', 'Customer gave wrong address', 'resolved'),
(3, 'Payment Problem', 'Card payment failed', 'open');

-- =========================================
-- PLATFORM SETTINGS TABLE DATA
-- =========================================
INSERT INTO platform_settings (
    setting_key,
    setting_value
)
VALUES
('commission_rate', '10'),
('delivery_fee_base', '50'),
('max_delivery_radius', '10'),
('estimated_delivery_time', '30'),
('platform_name', 'FoodExpress');