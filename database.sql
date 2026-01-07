-- =============================================
-- TechShop Database Schema - MySQL (XAMPP)
-- Website Bán Thiết Bị Máy Tính
-- =============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS computer_shop 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE computer_shop;

-- =============================================
-- Bảng Users (Người dùng)
-- =============================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'employee', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    avatar VARCHAR(255),
    email_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(100),
    remember_token VARCHAR(100),
    token_expiry DATETIME,
    reset_token VARCHAR(100),
    reset_expiry DATETIME,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- Bảng User Addresses (Địa chỉ người dùng)
-- =============================================
DROP TABLE IF EXISTS user_addresses;
CREATE TABLE user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    ward VARCHAR(100),
    district VARCHAR(100),
    city VARCHAR(100) NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Categories (Danh mục)
-- =============================================
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent_id (parent_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Products (Sản phẩm)
-- =============================================
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(500),
    price DECIMAL(15,0) NOT NULL,
    sale_price DECIMAL(15,0) DEFAULT NULL,
    category_id INT,
    brand VARCHAR(100),
    sku VARCHAR(50) UNIQUE,
    stock INT DEFAULT 0,
    featured TINYINT(1) DEFAULT 0,
    status ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active',
    rating DECIMAL(2,1) DEFAULT 0,
    review_count INT DEFAULT 0,
    sold_count INT DEFAULT 0,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_category_id (category_id),
    INDEX idx_brand (brand),
    INDEX idx_price (price),
    INDEX idx_status (status),
    INDEX idx_featured (featured),
    FULLTEXT idx_search (name, description)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Product Images (Hình ảnh sản phẩm)
-- =============================================
DROP TABLE IF EXISTS product_images;
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Product Specifications (Thông số kỹ thuật)
-- =============================================
DROP TABLE IF EXISTS product_specifications;
CREATE TABLE product_specifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    spec_name VARCHAR(100) NOT NULL,
    spec_value VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Orders (Đơn hàng)
-- =============================================
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(20) NOT NULL UNIQUE,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_ward VARCHAR(100),
    shipping_district VARCHAR(100),
    shipping_city VARCHAR(100) NOT NULL,
    subtotal DECIMAL(15,0) NOT NULL,
    shipping_fee DECIMAL(15,0) DEFAULT 0,
    discount DECIMAL(15,0) DEFAULT 0,
    total DECIMAL(15,0) NOT NULL,
    payment_method ENUM('cod', 'bank_transfer', 'momo', 'vnpay', 'credit_card') DEFAULT 'cod',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    status ENUM('pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled', 'returned') DEFAULT 'pending',
    note TEXT,
    admin_note TEXT,
    assigned_employee INT,
    cancelled_reason VARCHAR(500),
    delivered_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_employee) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_number (order_number),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Order Items (Chi tiết đơn hàng)
-- =============================================
DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    product_sku VARCHAR(50),
    price DECIMAL(15,0) NOT NULL,
    quantity INT NOT NULL,
    total DECIMAL(15,0) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Order History (Lịch sử đơn hàng)
-- =============================================
DROP TABLE IF EXISTS order_history;
CREATE TABLE order_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    note TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Reviews (Đánh giá)
-- =============================================
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(200),
    content TEXT,
    pros TEXT,
    cons TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    helpful_count INT DEFAULT 0,
    reply TEXT,
    reply_by INT,
    reply_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (reply_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_product_id (product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_rating (rating)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Review Images (Hình ảnh đánh giá)
-- =============================================
DROP TABLE IF EXISTS review_images;
CREATE TABLE review_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    INDEX idx_review_id (review_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Review Helpful (Đánh giá hữu ích)
-- =============================================
DROP TABLE IF EXISTS review_helpful;
CREATE TABLE review_helpful (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_review_user (review_id, user_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Carts (Giỏ hàng)
-- =============================================
DROP TABLE IF EXISTS carts;
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    session_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Cart Items (Chi tiết giỏ hàng)
-- =============================================
DROP TABLE IF EXISTS cart_items;
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_product (cart_id, product_id),
    INDEX idx_cart_id (cart_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Wishlist (Danh sách yêu thích)
-- =============================================
DROP TABLE IF EXISTS wishlist;
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Conversations (Cuộc hội thoại)
-- =============================================
DROP TABLE IF EXISTS conversations;
CREATE TABLE conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assigned_to INT,
    subject VARCHAR(200),
    status ENUM('open', 'pending', 'closed') DEFAULT 'open',
    last_message_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Messages (Tin nhắn)
-- =============================================
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    sender_type ENUM('user', 'employee', 'admin', 'system') NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    read_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_conversation_id (conversation_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Contacts (Liên hệ)
-- =============================================
DROP TABLE IF EXISTS contacts;
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'read', 'replied') DEFAULT 'pending',
    reply TEXT,
    replied_by INT,
    replied_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Coupons (Mã giảm giá)
-- =============================================
DROP TABLE IF EXISTS coupons;
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    discount_type ENUM('percent', 'fixed') NOT NULL,
    discount_value DECIMAL(15,2) NOT NULL,
    min_order_value DECIMAL(15,0) DEFAULT 0,
    max_discount DECIMAL(15,0),
    usage_limit INT,
    used_count INT DEFAULT 0,
    start_date DATETIME,
    end_date DATETIME,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- Bảng Settings (Cài đặt)
-- =============================================
DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key),
    INDEX idx_group (setting_group)
) ENGINE=InnoDB;

-- =============================================
-- Insert Default Settings
-- =============================================
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'TechShop', 'general'),
('site_email', 'contact@techshop.com', 'general'),
('site_phone', '1900 xxxx', 'general'),
('site_address', '123 Nguyễn Văn Linh, Quận 7, TP.HCM', 'general'),
('free_shipping_threshold', '500000', 'shipping'),
('default_shipping_fee', '30000', 'shipping'),
('tax_rate', '10', 'tax');

-- =============================================
-- Insert Sample Data
-- =============================================

-- Insert Admin User
INSERT INTO users (name, email, password, phone, role, status, email_verified) VALUES
('Admin TechShop', 'admin@techshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 'admin', 'active', 1),
('Nhân viên TechShop', 'employee@techshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234568', 'employee', 'active', 1),
('Nguyễn Văn A', 'user@techshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234569', 'user', 'active', 1);

-- Insert User Address
INSERT INTO user_addresses (user_id, name, phone, address, ward, district, city, is_default) VALUES
(1, 'Admin TechShop', '0901234567', '123 Nguyễn Văn Linh', 'Phường 1', 'Quận 7', 'TP. Hồ Chí Minh', 1),
(3, 'Nguyễn Văn A', '0901234569', '456 Lê Lợi', 'Phường Bến Thành', 'Quận 1', 'TP. Hồ Chí Minh', 1);

-- Insert Categories
INSERT INTO categories (name, slug, description, sort_order, status) VALUES
('Laptop', 'laptop', 'Laptop gaming, văn phòng, đồ họa các thương hiệu', 1, 'active'),
('PC Gaming', 'pc-gaming', 'Máy tính để bàn gaming cấu hình cao', 2, 'active'),
('Linh kiện', 'linh-kien', 'CPU, GPU, RAM, SSD, HDD và các linh kiện máy tính', 3, 'active'),
('Màn hình', 'man-hinh', 'Màn hình gaming, đồ họa, văn phòng', 4, 'active'),
('Bàn phím', 'ban-phim', 'Bàn phím cơ, bàn phím gaming, văn phòng', 5, 'active'),
('Chuột', 'chuot', 'Chuột gaming, chuột văn phòng, chuột không dây', 6, 'active'),
('Tai nghe', 'tai-nghe', 'Tai nghe gaming, tai nghe âm nhạc', 7, 'active'),
('Phụ kiện', 'phu-kien', 'Balo, túi chống sốc, đế tản nhiệt, hub USB', 8, 'active');

-- Insert Products
INSERT INTO products (name, slug, description, short_description, price, sale_price, category_id, brand, sku, stock, featured, status, rating, review_count, sold_count, views) VALUES
('ASUS ROG Strix G15 Gaming', 'asus-rog-strix-g15-gaming', 'Laptop gaming cao cấp với AMD Ryzen 9, RTX 3070, màn hình 300Hz', 'AMD Ryzen 9 5900HX, RTX 3070 8GB, 16GB RAM, 1TB SSD', 45990000, 42990000, 1, 'ASUS', 'ASUS-ROG-G15-001', 25, 1, 'active', 4.8, 156, 342, 15680),
('MacBook Pro 14 M3 Pro', 'macbook-pro-14-m3-pro', 'MacBook Pro 14 inch với chip M3 Pro, màn hình Liquid Retina XDR', 'Apple M3 Pro, 18GB RAM, 512GB SSD, Liquid Retina XDR', 52990000, NULL, 1, 'Apple', 'MBP-14-M3PRO-001', 15, 1, 'active', 4.9, 89, 156, 12450),
('Lenovo Legion 5 Pro', 'lenovo-legion-5-pro', 'Laptop gaming với màn hình 16" 2K 165Hz, RTX 3060', 'AMD Ryzen 7 5800H, RTX 3060 6GB, 16GB RAM, 512GB SSD', 32990000, 29990000, 1, 'Lenovo', 'LNV-L5PRO-001', 30, 1, 'active', 4.7, 234, 567, 23400),
('LG UltraGear 27GP850-B', 'lg-ultragear-27gp850-b', 'Màn hình gaming 27" Nano IPS 180Hz 1ms', '27" QHD Nano IPS, 180Hz, 1ms, HDR400, G-Sync Compatible', 12990000, 10990000, 4, 'LG', 'LG-27GP850B-001', 45, 1, 'active', 4.6, 178, 423, 18900),
('Samsung Odyssey G7 32"', 'samsung-odyssey-g7-32', 'Màn hình gaming cong 1000R, 240Hz', '32" QHD VA Curved, 240Hz, 1ms, QLED, G-Sync Compatible', 16990000, 14990000, 4, 'Samsung', 'SS-G7-32-001', 20, 0, 'active', 4.7, 134, 298, 14500),
('Logitech G Pro X Mechanical', 'logitech-g-pro-x-mechanical', 'Bàn phím cơ gaming với hot-swappable switches', 'TKL Layout, Hot-swappable, GX Blue Switches, RGB', 3290000, 2890000, 5, 'Logitech', 'LG-GPRO-X-001', 60, 1, 'active', 4.5, 267, 789, 25600),
('Razer BlackWidow V3 Pro', 'razer-blackwidow-v3-pro', 'Bàn phím cơ không dây với Razer Green Switches', 'Full-size, Wireless/BT/USB-C, Razer Green, RGB Chroma', 5490000, NULL, 5, 'Razer', 'RZ-BWV3PRO-001', 35, 0, 'active', 4.6, 145, 412, 16800),
('Logitech G Pro X Superlight', 'logitech-g-pro-x-superlight', 'Chuột gaming không dây siêu nhẹ 63g', '63g Wireless, HERO 25K Sensor, 70h Battery', 3490000, 2990000, 6, 'Logitech', 'LG-GPRO-XS-001', 80, 1, 'active', 4.9, 456, 1234, 45600),
('Razer DeathAdder V3 Pro', 'razer-deathadder-v3-pro', 'Chuột gaming không dây với thiết kế ergonomic', '63g Wireless, Focus Pro 30K Sensor, 90h Battery', 3990000, NULL, 6, 'Razer', 'RZ-DAV3PRO-001', 50, 0, 'active', 4.8, 312, 867, 32100),
('HyperX Cloud Alpha Wireless', 'hyperx-cloud-alpha-wireless', 'Tai nghe gaming không dây với pin 300 giờ', 'Wireless, 300h Battery, DTS Headphone:X, 50mm Drivers', 4990000, 4490000, 7, 'HyperX', 'HX-CA-W-001', 40, 1, 'active', 4.7, 198, 534, 21300),
('ASUS ROG Strix RTX 4070 Ti', 'asus-rog-strix-rtx-4070-ti', 'Card đồ họa cao cấp với thiết kế tản nhiệt 3 quạt', '12GB GDDR6X, OC Edition, Axial-tech Fans, Aura Sync', 25990000, 23990000, 3, 'ASUS', 'ASUS-4070TI-ROG-001', 15, 1, 'active', 4.8, 87, 178, 34500),
('MSI GeForce RTX 4060 Gaming X', 'msi-rtx-4060-gaming-x', 'Card đồ họa tầm trung với hiệu năng mạnh mẽ', '8GB GDDR6, Twin Frozr 9, Mystic Light RGB', 9990000, NULL, 3, 'MSI', 'MSI-4060-GX-001', 40, 0, 'active', 4.5, 156, 423, 28700);

-- Insert Product Images
INSERT INTO product_images (product_id, image_url, is_primary, sort_order) VALUES
(1, '/uploads/products/asus-rog-g15-1.jpg', 1, 1),
(1, '/uploads/products/asus-rog-g15-2.jpg', 0, 2),
(2, '/uploads/products/macbook-pro-14-1.jpg', 1, 1),
(3, '/uploads/products/lenovo-legion-5-pro-1.jpg', 1, 1),
(4, '/uploads/products/lg-27gp850-1.jpg', 1, 1),
(5, '/uploads/products/samsung-g7-1.jpg', 1, 1),
(6, '/uploads/products/logitech-gpro-x-1.jpg', 1, 1),
(7, '/uploads/products/razer-bw-v3-1.jpg', 1, 1),
(8, '/uploads/products/logitech-superlight-1.jpg', 1, 1),
(9, '/uploads/products/razer-dav3-1.jpg', 1, 1),
(10, '/uploads/products/hyperx-alpha-wireless-1.jpg', 1, 1),
(11, '/uploads/products/asus-4070ti-1.jpg', 1, 1),
(12, '/uploads/products/msi-4060-1.jpg', 1, 1);

-- Insert Product Specifications
INSERT INTO product_specifications (product_id, spec_name, spec_value, sort_order) VALUES
(1, 'CPU', 'AMD Ryzen 9 5900HX', 1),
(1, 'GPU', 'NVIDIA RTX 3070 8GB', 2),
(1, 'RAM', '16GB DDR4 3200MHz', 3),
(1, 'Storage', '1TB NVMe SSD', 4),
(1, 'Display', '15.6" FHD 300Hz IPS', 5),
(1, 'Battery', '90Wh', 6),
(1, 'Weight', '2.3kg', 7),
(2, 'CPU', 'Apple M3 Pro', 1),
(2, 'GPU', 'M3 Pro 14-core', 2),
(2, 'RAM', '18GB Unified Memory', 3),
(2, 'Storage', '512GB SSD', 4),
(2, 'Display', '14.2" Liquid Retina XDR', 5),
(2, 'Battery', 'Up to 17 hours', 6),
(2, 'Weight', '1.6kg', 7),
(8, 'Sensor', 'HERO 25K', 1),
(8, 'DPI', '100-25,600', 2),
(8, 'Connection', 'LIGHTSPEED Wireless', 3),
(8, 'Weight', '63g', 4),
(8, 'Battery', '70 hours', 5);

-- =============================================
-- Note: Password for all users is "password"
-- =============================================
