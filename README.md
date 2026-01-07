# TechShop - Website Bán Thiết Bị Máy Tính

Website thương mại điện tử bán thiết bị máy tính được xây dựng bằng PHP và MySQL (XAMPP).

## Tính năng

### Người dùng (User)
- Đăng ký, đăng nhập, quên mật khẩu
- Duyệt sản phẩm theo danh mục
- Tìm kiếm và lọc sản phẩm
- Xem chi tiết và so sánh sản phẩm
- Thêm vào giỏ hàng / wishlist
- Đặt hàng và theo dõi đơn hàng
- Đánh giá sản phẩm
- Quản lý hồ sơ cá nhân
- Chat hỗ trợ trực tuyến

### Nhân viên (Employee)
- Quản lý và xử lý đơn hàng
- Duyệt đánh giá sản phẩm
- Hỗ trợ khách hàng qua chat
- Xem thống kê đơn hàng

### Quản trị viên (Admin)
- Dashboard thống kê tổng quan
- Quản lý sản phẩm (CRUD)
- Quản lý danh mục
- Quản lý đơn hàng
- Quản lý người dùng và phân quyền
- Quản lý đánh giá

## Yêu cầu hệ thống

- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache với mod_rewrite enabled

## Cài đặt

### 1. Cài đặt XAMPP

1. Tải và cài đặt XAMPP từ [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Khởi động Apache và MySQL từ XAMPP Control Panel

### 2. Clone/Copy dự án

Copy thư mục dự án vào `C:\xampp\htdocs\` hoặc thư mục htdocs của XAMPP.

### 3. Tạo Database

#### Cách 1: Sử dụng phpMyAdmin
1. Mở trình duyệt và truy cập: `http://localhost/phpmyadmin`
2. Click "New" để tạo database mới
3. Nhập tên database: `computer_shop`
4. Chọn Collation: `utf8mb4_unicode_ci`
5. Click "Create"
6. Chọn database `computer_shop` vừa tạo
7. Click tab "Import"
8. Chọn file `database.sql` từ thư mục dự án
9. Click "Go" để import

#### Cách 2: Sử dụng Command Line
```bash
# Mở Command Prompt và chạy:
cd C:\xampp\mysql\bin
mysql -u root -p

# Trong MySQL prompt:
CREATE DATABASE computer_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE computer_shop;
SOURCE D:/doan_web_php/database.sql;
```

### 4. Cấu hình Database

Mở file `config/database.php` và kiểm tra thông tin kết nối (mặc định phù hợp với XAMPP):

```php
$host = 'localhost';
$dbname = 'computer_shop';
$username = 'root';
$password = ''; // XAMPP mặc định không có password
```

### 5. Cấu hình Apache (nếu cần Virtual Host)

Thêm vào file `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName techshop.local
    DocumentRoot "D:/doan_web_php"
    
    <Directory "D:/doan_web_php">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 6. Thêm vào file hosts

```
127.0.0.1 techshop.local
```

### 7. Tạo thư mục uploads

```bash
mkdir -p uploads/products
mkdir -p uploads/avatars
mkdir -p uploads/reviews
mkdir -p uploads/categories
mkdir -p uploads/chat
chmod -R 755 uploads
```

## Cấu trúc thư mục

```
doan_web_php/
├── api/                    # API endpoints
│   ├── admin/              # Admin APIs
│   ├── employee/           # Employee APIs
│   ├── auth.php            # Authentication API
│   ├── cart.php            # Cart API
│   ├── contact.php         # Contact form API
│   ├── orders.php          # Orders API
│   ├── products.php        # Products API
│   ├── reviews.php         # Reviews API
│   ├── user.php            # User profile API
│   └── wishlist.php        # Wishlist API
├── assets/                 # Static assets
│   ├── css/                # CSS files
│   └── js/                 # JavaScript files
├── config/                 # Configuration files
│   └── database.php        # Database connection
├── controllers/            # Controllers (MVC)
│   ├── AdminController.php
│   ├── AuthController.php
│   ├── EmployeeController.php
│   └── UserController.php
├── models/                 # Models (MVC)
│   ├── Cart.php
│   ├── Category.php
│   ├── Conversation.php
│   ├── Order.php
│   ├── Product.php
│   ├── Review.php
│   └── User.php
├── views/                  # Views (MVC)
│   ├── admin/              # Admin views
│   ├── auth/               # Auth views (login, register)
│   ├── employee/           # Employee views
│   ├── errors/             # Error pages
│   ├── layouts/            # Layout templates
│   ├── pages/              # Static pages
│   └── user/               # User views
├── uploads/                # Uploaded files
├── .htaccess               # Apache rewrite rules
├── composer.json           # Composer dependencies
├── index.php               # Main entry point / Router
└── README.md               # This file
```

## Tài khoản mặc định

### Admin
- Email: admin@techshop.com
- Password: password

### Employee
- Email: employee@techshop.com
- Password: password

### User
- Email: user@techshop.com
- Password: password

## API Documentation

### Authentication

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/auth.php?action=login` | POST | Đăng nhập |
| `/api/auth.php?action=register` | POST | Đăng ký |
| `/api/auth.php?action=logout` | POST | Đăng xuất |
| `/api/auth.php?action=forgot-password` | POST | Quên mật khẩu |
| `/api/auth.php?action=check-auth` | GET | Kiểm tra đăng nhập |

### Products

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/products.php?action=list` | POST | Danh sách sản phẩm |
| `/api/products.php?action=search` | POST | Tìm kiếm sản phẩm |
| `/api/products.php?action=detail` | POST | Chi tiết sản phẩm |
| `/api/products.php?action=quick-view` | POST | Xem nhanh sản phẩm |
| `/api/products.php?action=related` | POST | Sản phẩm liên quan |
| `/api/products.php?action=featured` | GET | Sản phẩm nổi bật |
| `/api/products.php?action=bestselling` | GET | Sản phẩm bán chạy |

### Cart

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/cart.php?action=get` | GET | Lấy giỏ hàng |
| `/api/cart.php?action=add` | POST | Thêm sản phẩm |
| `/api/cart.php?action=update` | POST | Cập nhật số lượng |
| `/api/cart.php?action=remove` | POST | Xóa sản phẩm |
| `/api/cart.php?action=clear` | POST | Xóa toàn bộ |
| `/api/cart.php?action=count` | GET | Đếm số sản phẩm |

### Orders

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/orders.php?action=create` | POST | Tạo đơn hàng |
| `/api/orders.php?action=list` | GET | Danh sách đơn hàng |
| `/api/orders.php?action=detail` | GET | Chi tiết đơn hàng |
| `/api/orders.php?action=cancel` | POST | Hủy đơn hàng |
| `/api/orders.php?action=track` | POST | Theo dõi đơn hàng |

### Reviews

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/reviews.php?action=list` | GET | Danh sách đánh giá |
| `/api/reviews.php?action=create` | POST | Tạo đánh giá |
| `/api/reviews.php?action=helpful` | POST | Đánh dấu hữu ích |

### Wishlist

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/wishlist.php?action=get` | GET | Lấy danh sách yêu thích |
| `/api/wishlist.php?action=toggle` | POST | Thêm/xóa yêu thích |

## Công nghệ sử dụng

- **Backend**: PHP 7.4+ với PDO
- **Database**: MySQL 5.7+ (XAMPP)
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Font Awesome 6.5
- **Charts**: Chart.js
- **DataTables**: DataTables 1.13
- **Alerts**: SweetAlert2
- **Editor**: CKEditor 5

## Bảo mật

- Password được hash với `password_hash()` (bcrypt)
- Session-based authentication
- CSRF protection ready
- XSS prevention với `htmlspecialchars()`
- SQL Injection prevention với PDO Prepared Statements
- Input validation và sanitization

## Database Schema

Xem file `database.sql` để biết chi tiết về cấu trúc database bao gồm:
- **users** - Thông tin người dùng
- **user_addresses** - Địa chỉ giao hàng
- **categories** - Danh mục sản phẩm
- **products** - Sản phẩm
- **product_images** - Hình ảnh sản phẩm
- **product_specifications** - Thông số kỹ thuật
- **orders** - Đơn hàng
- **order_items** - Chi tiết đơn hàng
- **reviews** - Đánh giá sản phẩm
- **carts** - Giỏ hàng
- **wishlist** - Danh sách yêu thích
- **conversations** - Chat hỗ trợ
- **contacts** - Liên hệ
- **coupons** - Mã giảm giá

## License

MIT License
