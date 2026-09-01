CREATE DATABASE IF NOT EXISTS ShopNest;
USE ShopNest;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT '',
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin'
);

CREATE TABLE IF NOT EXISTS sellers (
    seller_id INT AUTO_INCREMENT PRIMARY KEY,
    shop_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending'
);

CREATE TABLE IF NOT EXISTS delivery_agents (
    agent_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NULL,
    category_id INT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES sellers(seller_id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    UNIQUE KEY customer_product (customer_id, product_id),
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    method VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS delivery (
    delivery_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    agent_name VARCHAR(150) DEFAULT '',
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    delivery_date DATE NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY customer_product_review (product_id, customer_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO categories (category_name) VALUES
('Electronics'), ('Fashion'), ('Accessories'), ('Home & Study'), ('Travel');

INSERT IGNORE INTO admins (name, email, password, role) VALUES
('ShopNest Admin', 'admin@shopnest.com', '$2y$12$LaJ7W3AFhnAtLILQIIvPvuTOB.GNBLw/My/eRxzYBfKsljPbiq.3i', 'admin');

INSERT IGNORE INTO sellers (shop_name, email, password, status) VALUES
('Tech World', 'seller@shopnest.com', '$2y$12$J2TQgKRbE69xaZyd71Pd/uNR.Wd1paCOOXzXL1GYybWlfG98B3Mcm', 'approved');

INSERT IGNORE INTO delivery_agents (name, email, password, phone) VALUES
('Delivery Agent', 'delivery@shopnest.com', '$2y$12$ra4ca9S9VitbuNCqXyyiVOfO/b1O5QHAKBRrMbwl3tgQDCRc65Vhu', '01700000000');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Wireless Headphones', 250.00, 20,
'High-quality wireless headphones with clear sound and comfortable design.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Electronics'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Wireless Headphones');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Smart Watch', 950.00, 15,
'Modern smartwatch with fitness tracking and useful everyday features.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Electronics'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Smart Watch');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Laptop Backpack', 1200.00, 12,
'Durable and stylish backpack suitable for laptops, books, and daily use.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Travel'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Laptop Backpack');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Smartphone', 100000.00, 8,
'Modern smartphone with a sleek design and powerful everyday performance.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Electronics'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Smartphone');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Running Shoes', 2500.00, 25,
'Comfortable and lightweight shoes suitable for everyday activities.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Fashion'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Running Shoes');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Cotton T-Shirt', 450.00, 30,
'Comfortable cotton t-shirt with a simple and stylish design.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Fashion'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Cotton T-Shirt');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Travel Water Bottle', 250.00, 40,
'Reusable water bottle designed for travel, work, and everyday use.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Travel'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Travel Water Bottle');

INSERT INTO products (seller_id, category_id, name, price, stock, description)
SELECT s.seller_id, c.category_id, 'Desk Lamp', 600.00, 18,
'Modern desk lamp providing comfortable lighting for work and study.'
FROM sellers s, categories c WHERE s.email='seller@shopnest.com' AND c.category_name='Home & Study'
AND NOT EXISTS (SELECT 1 FROM products WHERE name='Desk Lamp');

