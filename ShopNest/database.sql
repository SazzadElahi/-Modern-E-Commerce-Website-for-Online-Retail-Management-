CREATE DATABASE IF NOT EXISTS ShopNest;
USE ShopNest;
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,

    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    
    FOREIGN KEY (order_id)
    REFERENCES orders(id)
    ON DELETE CASCADE,
    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE SET NULL
);

INSERT INTO products (name, price, image, description) VALUES

(
    'Wireless Headphones',
    250.00,
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e',
    'High-quality wireless headphones with clear sound and comfortable design.'
),

(
    'Smart Watch',
    950.00,
    'https://images.unsplash.com/photo-1523275335684-37898b6baf30',
    'Modern smartwatch with fitness tracking and useful everyday features.'
),

(
    'Laptop Backpack',
    1200.00,
    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62',
    'Durable and stylish backpack suitable for laptops, books, and daily use.'
),

(
    'Smartphone',
    100000.00,
    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9',
    'Modern smartphone with a sleek design and powerful everyday performance.'
),

(
    'Running Shoes',
    2500.00,
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff',
    'Comfortable and lightweight shoes suitable for everyday activities.'
),

(
    'Cotton T-Shirt',
    450.00,
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab',
    'Comfortable cotton t-shirt with a simple and stylish design.'
),

(
    'Travel Water Bottle',
    250.00,
    'https://images.unsplash.com/photo-1602143407151-7111542de6e8',
    'Reusable water bottle designed for travel, work, and everyday use.'
),

(
    'Desk Lamp',
    600.00,
    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c',
    'Modern desk lamp providing comfortable lighting for work and study.'
);