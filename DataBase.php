-- Create the honey_ecommerce database
CREATE DATABASE honey_ecommerce;



-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create orders table with additional timestamps
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_data TEXT,
    shipping_address VARCHAR(255),
    order_date DATETIME NOT NULL,
    admin_viewed TIMESTAMP NULL DEFAULT NULL,
    time_to_view INT,
    ready_date TIMESTAMP NULL DEFAULT NULL,
    delivery_date TIMESTAMP NULL DEFAULT NULL,
    received_date TIMESTAMP NULL DEFAULT NULL,
    admin_viewed_at TIMESTAMP NULL DEFAULT NULL,
    ready_at TIMESTAMP NULL DEFAULT NULL,
    delivery_taken_at TIMESTAMP NULL DEFAULT NULL,
    received_at TIMESTAMP NULL DEFAULT NULL,
    status ENUM('pending', 'ready', 'out_for_delivery', 'received') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Create a sample cart table (optional, for temporary cart management)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert an admin account
INSERT INTO users (name, email, password, role) 
VALUES ('Admin User', 'admin@example.com', 'adminpassword', 'admin');

-- Insert a customer account
INSERT INTO users (name, email, password, role) 
VALUES ('John Doe', 'john@example.com', 'userpassword', 'customer');

-- Insert first product
INSERT INTO products (name, description, price, image, stock) 
VALUES ('Organic Wild Honey', 'Pure natural honey sourced from wild bees.', 25.99, 'wild_honey.jpg', 50);

-- Insert second product
INSERT INTO products (name, description, price, image, stock) 
VALUES ('Raw Acacia Honey', 'Delicious raw Acacia honey with a light floral taste.', 30.50, 'acacia_honey.jpg', 30);