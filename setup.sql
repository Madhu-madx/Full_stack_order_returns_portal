-- Run this entire file in phpMyAdmin > SQL tab

CREATE DATABASE IF NOT EXISTS returns_db;
USE returns_db;

-- Table: orders (sample orders to test against)
CREATE TABLE IF NOT EXISTS orders (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  order_id      VARCHAR(20)    NOT NULL UNIQUE,
  customer_name VARCHAR(100)   NOT NULL,
  item_name     VARCHAR(100)   NOT NULL,
  purchase_date DATE           NOT NULL,
  amount        DECIMAL(10,2)  NOT NULL
);

-- Table: return_requests (submitted returns)
CREATE TABLE IF NOT EXISTS return_requests (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  order_id       VARCHAR(20)  NOT NULL,
  customer_name  VARCHAR(100) NOT NULL,
  item_name      VARCHAR(100) NOT NULL,
  purchase_date  DATE         NOT NULL,
  reason         TEXT         NOT NULL,
  item_condition ENUM('unopened', 'opened', 'damaged') NOT NULL,
  status         ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  submitted_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  reviewed_at    TIMESTAMP NULL
);

-- Sample orders (use these Order IDs to test the app)
-- ORD001, ORD002, ORD003 are within 30 days → eligible
-- ORD004 is older than 30 days → return window expired
INSERT INTO orders (order_id, customer_name, item_name, purchase_date, amount) VALUES
('ORD001', 'Priya Sharma',   'Wireless Headphones', CURDATE() - INTERVAL 5  DAY, 2999.00),
('ORD002', 'Rahul Verma',    'Running Shoes',        CURDATE() - INTERVAL 12 DAY, 1599.00),
('ORD003', 'Ananya Iyer',    'Yoga Mat',             CURDATE() - INTERVAL 2  DAY,  899.00),
('ORD004', 'Kiran Nair',     'Coffee Maker',         CURDATE() - INTERVAL 45 DAY, 3499.00),
('ORD005', 'Deepa Menon',    'Bluetooth Speaker',    CURDATE() - INTERVAL 20 DAY, 1999.00);
