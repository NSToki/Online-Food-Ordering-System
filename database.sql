-- ============================================================
-- FoodPathai - Online Food Ordering System Database Schema
-- Database: online_food_ordering_system
-- ============================================================

CREATE DATABASE IF NOT EXISTS `online_food_ordering_system`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `online_food_ordering_system`;

-- ─── USERS ───────────────────────────────────────────────────
-- Covers: admin, manager, customer, agent (role-based)
CREATE TABLE IF NOT EXISTS `users` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`         VARCHAR(120)  NOT NULL,
    `email`        VARCHAR(180)  NOT NULL UNIQUE,
    `password_hash`VARCHAR(255)  NOT NULL,
    `phone`        VARCHAR(20)   DEFAULT NULL,
    `role`         ENUM('admin','manager','customer','agent') NOT NULL DEFAULT 'customer',
    `profile_pic`  LONGBLOB      DEFAULT NULL,
    `is_active`    TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── RESTAURANTS ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `restaurants` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `manager_id`   INT UNSIGNED NOT NULL,
    `name`         VARCHAR(180) NOT NULL,
    `cuisine_type` VARCHAR(100) DEFAULT NULL,
    `address`      VARCHAR(255) DEFAULT NULL,
    `city`         VARCHAR(100) DEFAULT NULL,
    `is_approved`  TINYINT(1)   NOT NULL DEFAULT 0,
    `is_open`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── DELIVERY AGENTS ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `delivery_agents` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id`      INT UNSIGNED NOT NULL,
    `vehicle_type` VARCHAR(60)  NOT NULL DEFAULT 'Motorcycle',
    `is_online`    TINYINT(1)   NOT NULL DEFAULT 0,
    `is_approved`  TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── ORDERS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orders` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `customer_id`   INT UNSIGNED NOT NULL,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `agent_id`      INT UNSIGNED DEFAULT NULL,
    `status`        ENUM('pending','accepted','preparing','ready','picked_up','on_the_way','delivered','cancelled')
                    NOT NULL DEFAULT 'pending',
    `total_amount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `delivery_fee`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `created_at`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`)   REFERENCES `users`(`id`)        ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`)  ON DELETE CASCADE,
    FOREIGN KEY (`agent_id`)      REFERENCES `delivery_agents`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── COMPLAINTS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `complaints` (
    `id`           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `submitter_id` INT UNSIGNED NOT NULL,
    `subject`      VARCHAR(255) NOT NULL,
    `description`  TEXT         NOT NULL,
    `status`       ENUM('open','resolved') NOT NULL DEFAULT 'open',
    `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`submitter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── PLATFORM SETTINGS ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS `platform_settings` (
    `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key`   VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT         DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default platform settings
INSERT IGNORE INTO `platform_settings` (`setting_key`, `setting_value`) VALUES
    ('commission_rate_pct',  '10'),
    ('base_delivery_fee',    '2.50'),
    ('delivery_fee_per_km',  '0.50'),
    ('cuisine_categories',   '["Burger","Pizza","Sushi","Biryani","Salad","Dessert","Chinese","Indian","Thai","Fast Food"]');
