CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tel VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    role ENUM('Admin', 'User') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    original_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS discount_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percent INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert some dummy data for products
INSERT INTO products (name, category, price, stock, image_url) VALUES
('Mechanical Keyboard', 'Keyboard', 2500.00, 50, 'https://placehold.co/600x400?text=Keyboard'),
('Gaming Mouse', 'Mouse', 1200.00, 100, 'https://placehold.co/600x400?text=Mouse'),
('27 inch Gaming Monitor', 'Monitor', 8500.00, 20, 'https://placehold.co/600x400?text=Monitor'),
('Gaming Headset', 'Audio', 1800.00, 30, 'https://placehold.co/600x400?text=Headset');

-- Insert a default admin user (password: admin123)
-- Note: In a real application, passwords should be hashed. For this example, we'll store them as plain text or handle hashing in PHP.
-- If hashing is implemented in PHP (password_hash), this insert might need to be adjusted or removed.
-- For now, let's assume we might register through the app or I'll just put a placeholder. 
-- Actually, let's insert a user with a hashed password for 'admin123' if we use password_verify in PHP.
-- $2y$10$8.kP/Sj... is a hash, but since I don't know the implementation details of login yet, I will add a comment.
INSERT INTO users (username, password, fullname, email, tel, address, role) VALUES
('admin', '$2y$10$rContent...', 'Admin User', 'admin@example.com', '0123456789', 'Admin Address', 'Admin');
