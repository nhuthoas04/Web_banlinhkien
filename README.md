# 🖥️ TechShop - Website Bán Thiết Bị Máy Tính

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Website thương mại điện tử bán thiết bị máy tính được xây dựng bằng PHP thuần và MySQL, chạy trên XAMPP.

![TechShop Banner](assets/images/banners/banner1.jpg)

---

## 📋 Mục lục

- [Tính năng](#-tính-năng)
- [Demo](#-demo)
- [Yêu cầu hệ thống](#-yêu-cầu-hệ-thống)
- [Cài đặt](#-cài-đặt)
- [Cấu trúc dự án](#-cấu-trúc-dự-án)
- [API Documentation](#-api-documentation)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Bảo mật](#-bảo-mật)
- [Tài khoản mặc định](#-tài-khoản-mặc-định)
- [Screenshots](#-screenshots)
- [License](#-license)

---

## ✨ Tính năng

### 🔐 Xác thực & Tài khoản

| Tính năng | Mô tả |
|-----------|-------|
| ✅ Đăng ký tài khoản | Tạo tài khoản mới với email, số điện thoại |
| ✅ Đăng nhập | Đăng nhập bằng email/mật khẩu |
| ✅ Đăng nhập Google | OAuth 2.0 với tài khoản Google |
| ✅ Quên mật khẩu | Gửi email reset password qua SMTP |
| ✅ Đặt lại mật khẩu | Reset password với token bảo mật |
| ✅ Ghi nhớ đăng nhập | Remember me functionality |
| ✅ Phân quyền | 3 vai trò: User, Employee, Admin |

### 🛍️ Người dùng (User)

| Tính năng | Mô tả |
|-----------|-------|
| ✅ Trang chủ | Banner, sản phẩm nổi bật, bán chạy, mới nhất |
| ✅ Danh sách sản phẩm | Xem theo danh mục, lọc, sắp xếp |
| ✅ Chi tiết sản phẩm | Hình ảnh, thông số kỹ thuật, mô tả |
| ✅ Tìm kiếm sản phẩm | Tìm kiếm theo tên, danh mục, thương hiệu |
| ✅ Giỏ hàng | Thêm, sửa, xóa sản phẩm trong giỏ |
| ✅ Mua ngay | Chuyển thẳng đến thanh toán |
| ✅ Wishlist | Danh sách sản phẩm yêu thích |
| ✅ Đặt hàng | Checkout với thông tin giao hàng |
| ✅ Theo dõi đơn hàng | Xem trạng thái, lịch sử đơn hàng |
| ✅ Chi tiết đơn hàng | Xem chi tiết từng đơn hàng |
| ✅ Hủy đơn hàng | Hủy đơn khi chưa xử lý |
| ✅ Đánh giá sản phẩm | Đánh giá sao + bình luận |
| ✅ Quản lý đánh giá | Xem lại các đánh giá đã viết |
| ✅ Hồ sơ cá nhân | Cập nhật thông tin, avatar, mật khẩu |
| ✅ Chat hỗ trợ | Chat trực tiếp với nhân viên |
| ✅ Thông báo | Nhận thông báo đơn hàng |

### 👨‍💼 Nhân viên (Employee)

| Tính năng | Mô tả |
|-----------|-------|
| ✅ Dashboard | Thống kê đơn hàng cần xử lý |
| ✅ Quản lý đơn hàng | Xem, cập nhật trạng thái đơn hàng |
| ✅ Xử lý đơn hàng | Xác nhận, giao hàng, hoàn thành |
| ✅ Duyệt đánh giá | Phê duyệt/từ chối đánh giá |
| ✅ Chat hỗ trợ | Trả lời chat từ khách hàng |

### 👑 Quản trị viên (Admin)

| Tính năng | Mô tả |
|-----------|-------|
| ✅ Dashboard | Thống kê tổng quan (doanh thu, đơn hàng, users) |
| ✅ Biểu đồ doanh thu | Chart.js biểu đồ theo ngày/tuần/tháng |
| ✅ Quản lý sản phẩm | CRUD sản phẩm, upload hình ảnh |
| ✅ Xem trước sản phẩm | Preview sản phẩm trước khi lưu |
| ✅ Quản lý danh mục | CRUD danh mục sản phẩm |
| ✅ Quản lý đơn hàng | Xem tất cả đơn, cập nhật trạng thái |
| ✅ Thống kê doanh thu | Báo cáo doanh thu chi tiết |
| ✅ Quản lý người dùng | CRUD users, phân quyền, khóa tài khoản |
| ✅ Quản lý đánh giá | Xem, duyệt, xóa đánh giá |
| ✅ Quản lý chat | Xem lịch sử chat hỗ trợ |

### 📄 Trang thông tin

| Trang | Mô tả |
|-------|-------|
| ✅ Giới thiệu | Thông tin về TechShop |
| ✅ Liên hệ | Form liên hệ + thông tin liên lạc |
| ✅ Hướng dẫn mua hàng | Các bước mua hàng |
| ✅ Chính sách bảo hành | Thông tin bảo hành |
| ✅ Chính sách đổi trả | Quy định đổi trả hàng |

---

## 🎯 Demo

- **URL**: http://localhost/doan_web_php/
- **Admin**: http://localhost/doan_web_php/admin

---

## 💻 Yêu cầu hệ thống

| Yêu cầu | Phiên bản |
|---------|-----------|
| XAMPP | 8.0+ |
| PHP | 8.0+ |
| MySQL | 5.7+ |
| Apache | 2.4+ (với mod_rewrite) |

---

## 🚀 Cài đặt

### Bước 1: Cài đặt XAMPP

1. Tải XAMPP từ [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Cài đặt và khởi động **Apache** + **MySQL**

### Bước 2: Clone dự án

```bash
cd C:\xampp\htdocs
git clone <repository-url> doan_web_php
```

Hoặc copy thư mục dự án vào `C:\xampp\htdocs\doan_web_php`

### Bước 3: Tạo Database

1. Mở **phpMyAdmin**: http://localhost/phpmyadmin
2. Tạo database mới: `computer_shop` (utf8mb4_unicode_ci)
3. Import file `database.sql`

### Bước 4: Cấu hình

Kiểm tra file `config/database.php`:

```php
$host = 'localhost';
$dbname = 'computer_shop';
$username = 'root';
$password = ''; // XAMPP mặc định không có password
```

### Bước 5: Cấu hình Email (SMTP)

Để gửi email reset password, cấu hình trong `config/config.php`:

```php
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password'); // App Password 16 ký tự
```

**Tạo App Password Gmail:**
1. Bật 2FA tại: https://myaccount.google.com/security
2. Tạo App Password: https://myaccount.google.com/apppasswords

### Bước 6: Cấu hình Google OAuth (Tùy chọn)

Để đăng nhập bằng Google, cấu hình trong `config/config.php`:

```php
define('GOOGLE_CLIENT_ID', 'your-client-id');
define('GOOGLE_CLIENT_SECRET', 'your-client-secret');
define('GOOGLE_REDIRECT_URI', BASE_URL . 'google-callback');
```

### Bước 7: Truy cập website

Mở trình duyệt: http://localhost/doan_web_php/

---

## 📁 Cấu trúc dự án

```
doan_web_php/
├── 📂 api/                      # REST API endpoints
│   ├── 📂 admin/                # Admin APIs
│   │   ├── categories.php       # CRUD danh mục
│   │   ├── orders.php           # Quản lý đơn hàng
│   │   ├── products.php         # CRUD sản phẩm
│   │   ├── reviews.php          # Quản lý đánh giá
│   │   └── users.php            # Quản lý người dùng
│   ├── 📂 employee/             # Employee APIs
│   │   ├── chat.php             # Chat hỗ trợ
│   │   ├── orders.php           # Xử lý đơn hàng
│   │   └── reviews.php          # Duyệt đánh giá
│   ├── auth.php                 # Xác thực
│   ├── cart.php                 # Giỏ hàng
│   ├── contact.php              # Form liên hệ
│   ├── orders.php               # Đơn hàng user
│   ├── products.php             # Sản phẩm public
│   ├── reviews.php              # Đánh giá
│   ├── user.php                 # Hồ sơ user
│   └── wishlist.php             # Yêu thích
│
├── 📂 assets/                   # Static files
│   ├── 📂 css/
│   │   ├── admin.css            # Admin styles
│   │   ├── auth.css             # Login/Register styles
│   │   └── style.css            # Main styles
│   ├── 📂 images/
│   │   ├── banners/             # Banner images
│   │   ├── brands/              # Brand logos
│   │   ├── categories/          # Category images
│   │   └── products/            # Product images
│   └── 📂 js/
│       ├── admin.js             # Admin scripts
│       └── main.js              # Main scripts
│
├── 📂 config/                   # Configuration
│   ├── add_google_login.sql     # SQL: Thêm đăng nhập Google
│   ├── add_reset_password.sql   # SQL: Thêm reset password
│   ├── computer_shop.sql        # Database backup
│   ├── config.php               # App config (SMTP, OAuth)
│   ├── database.php             # Database connection
│   ├── fix_categories.sql       # SQL: Fix danh mục
│   └── update_specifications.sql # SQL: Update thông số
│
├── 📂 controllers/              # MVC Controllers
│   ├── AdminController.php      # Admin logic
│   ├── AuthController.php       # Auth logic (login, register, reset)
│   ├── EmployeeController.php   # Employee logic
│   └── UserController.php       # User logic
│
├── 📂 models/                   # MVC Models
│   ├── Brand.php                # Brand model
│   ├── Cart.php                 # Cart model
│   ├── Category.php             # Category model
│   ├── Chat.php                 # Chat model
│   ├── Conversation.php         # Conversation model
│   ├── Order.php                # Order model
│   ├── Product.php              # Product model
│   ├── Review.php               # Review model
│   └── User.php                 # User model
│
├── 📂 views/                    # MVC Views
│   ├── 📂 admin/                # Admin pages
│   │   ├── categories.php       # Quản lý danh mục
│   │   ├── chats.php            # Quản lý chat
│   │   ├── dashboard.php        # Dashboard
│   │   ├── order-detail.php     # Chi tiết đơn hàng
│   │   ├── orders.php           # Quản lý đơn hàng
│   │   ├── product-form.php     # Form thêm/sửa sản phẩm
│   │   ├── products.php         # Danh sách sản phẩm
│   │   ├── revenue.php          # Thống kê doanh thu
│   │   ├── reviews.php          # Quản lý đánh giá
│   │   └── users.php            # Quản lý users
│   ├── 📂 auth/                 # Authentication pages
│   │   ├── forgot-password.php  # Quên mật khẩu
│   │   ├── login.php            # Đăng nhập
│   │   ├── register.php         # Đăng ký
│   │   └── reset-password.php   # Đặt lại mật khẩu
│   ├── 📂 components/           # Reusable components
│   │   ├── account-sidebar.php  # Sidebar tài khoản
│   │   └── product-card.php     # Card sản phẩm
│   ├── 📂 employee/             # Employee pages
│   │   ├── chat.php             # Chat hỗ trợ
│   │   ├── dashboard.php        # Dashboard
│   │   ├── orders.php           # Quản lý đơn hàng
│   │   └── reviews.php          # Duyệt đánh giá
│   ├── 📂 errors/               # Error pages
│   │   ├── 404.php              # Not Found
│   │   └── 500.php              # Server Error
│   ├── 📂 layouts/              # Layout templates
│   │   ├── admin-footer.php     # Admin footer
│   │   ├── admin-header.php     # Admin header
│   │   ├── footer.php           # Main footer
│   │   └── header.php           # Main header
│   ├── 📂 pages/                # Static pages
│   │   ├── about.php            # Giới thiệu
│   │   ├── contact.php          # Liên hệ
│   │   ├── guide.php            # Hướng dẫn
│   │   ├── return-policy.php    # Đổi trả
│   │   └── warranty.php         # Bảo hành
│   └── 📂 user/                 # User pages
│       ├── cart.php             # Giỏ hàng
│       ├── chat.php             # Chat hỗ trợ
│       ├── checkout.php         # Thanh toán
│       ├── home.php             # Trang chủ
│       ├── my-reviews.php       # Đánh giá của tôi
│       ├── notifications.php    # Thông báo
│       ├── order-detail.php     # Chi tiết đơn hàng
│       ├── orders.php           # Đơn hàng của tôi
│       ├── product-detail.php   # Chi tiết sản phẩm
│       ├── products.php         # Danh sách sản phẩm
│       ├── profile.php          # Hồ sơ cá nhân
│       └── wishlist.php         # Yêu thích
│
├── 📂 uploads/                  # User uploads
│   └── products/                # Product images
│
├── .htaccess                    # Apache rewrite rules
├── database.sql                 # Database schema + data
├── index.php                    # Main router
└── README.md                    # Documentation
```

---

## 📡 API Documentation

### 🔐 Authentication

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/auth.php?action=login` | POST | Đăng nhập |
| `/api/auth.php?action=register` | POST | Đăng ký |
| `/api/auth.php?action=logout` | POST | Đăng xuất |
| `/api/auth.php?action=check` | GET | Kiểm tra đăng nhập |

### 📦 Products

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/products.php?action=list` | GET | Danh sách sản phẩm |
| `/api/products.php?action=search` | GET | Tìm kiếm |
| `/api/products.php?action=detail&id=X` | GET | Chi tiết sản phẩm |
| `/api/products.php?action=featured` | GET | Sản phẩm nổi bật |
| `/api/products.php?action=bestselling` | GET | Sản phẩm bán chạy |

### 🛒 Cart

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/cart.php?action=get` | GET | Lấy giỏ hàng |
| `/api/cart.php?action=add` | POST | Thêm sản phẩm |
| `/api/cart.php?action=update` | POST | Cập nhật số lượng |
| `/api/cart.php?action=remove` | POST | Xóa sản phẩm |
| `/api/cart.php?action=remove_multiple` | POST | Xóa nhiều sản phẩm |
| `/api/cart.php?action=clear` | POST | Xóa toàn bộ |
| `/api/cart.php?action=count` | GET | Đếm số sản phẩm |

### 📋 Orders

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/orders.php?action=create` | POST | Tạo đơn hàng |
| `/api/orders.php?action=list` | GET | Danh sách đơn hàng |
| `/api/orders.php?action=detail&id=X` | GET | Chi tiết đơn hàng |
| `/api/orders.php?action=cancel` | POST | Hủy đơn hàng |

### ⭐ Reviews

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/reviews.php?action=list` | GET | Danh sách đánh giá |
| `/api/reviews.php?action=create` | POST | Tạo đánh giá |
| `/api/reviews.php?action=helpful` | POST | Đánh dấu hữu ích |

### ❤️ Wishlist

| Endpoint | Method | Mô tả |
|----------|--------|-------|
| `/api/wishlist.php?action=get` | GET | Lấy wishlist |
| `/api/wishlist.php?action=toggle` | POST | Thêm/xóa yêu thích |
| `/api/wishlist.php?action=check` | POST | Kiểm tra yêu thích |

---

## 🛠️ Công nghệ sử dụng

### Backend
- **PHP 8.0+** - Server-side scripting
- **PDO** - Database abstraction layer
- **MySQL 5.7+** - Relational database

### Frontend
- **HTML5** - Markup
- **CSS3** - Styling
- **JavaScript ES6+** - Client-side scripting
- **Bootstrap 5.3** - CSS framework
- **Font Awesome 6.5** - Icons

### Libraries
- **Chart.js** - Biểu đồ thống kê
- **DataTables** - Bảng dữ liệu nâng cao
- **SweetAlert2** - Thông báo đẹp
- **CKEditor 5** - Rich text editor

### Email
- **SMTP Gmail** - Gửi email reset password
- **PHP mail()** - Fallback

### OAuth
- **Google OAuth 2.0** - Đăng nhập bằng Google

---

## 🔒 Bảo mật

| Tính năng | Mô tả |
|-----------|-------|
| Password Hashing | `password_hash()` với bcrypt |
| Session Authentication | Session-based login |
| CSRF Protection | Token-based protection |
| XSS Prevention | `htmlspecialchars()` escape |
| SQL Injection Prevention | PDO Prepared Statements |
| Input Validation | Server-side validation |
| Secure Password Reset | Token với thời hạn 1 giờ |

---

## 👤 Tài khoản mặc định

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| 👑 Admin | admin@techshop.com | password |
| 👨‍💼 Employee | employee@techshop.com | password |
| 👤 User | user@techshop.com | password |

---

## 📸 Screenshots

### Trang chủ
![Home](assets/images/screenshots/home.png)

### Trang sản phẩm
![Products](assets/images/screenshots/products.png)

### Giỏ hàng
![Cart](assets/images/screenshots/cart.png)

### Admin Dashboard
![Admin](assets/images/screenshots/admin.png)

---

## 📊 Database Schema

### Bảng chính

| Bảng | Mô tả |
|------|-------|
| `users` | Thông tin người dùng |
| `user_addresses` | Địa chỉ giao hàng |
| `categories` | Danh mục sản phẩm |
| `products` | Sản phẩm |
| `product_images` | Hình ảnh sản phẩm |
| `product_specifications` | Thông số kỹ thuật |
| `orders` | Đơn hàng |
| `order_items` | Chi tiết đơn hàng |
| `reviews` | Đánh giá sản phẩm |
| `carts` | Giỏ hàng |
| `cart_items` | Chi tiết giỏ hàng |
| `wishlist` | Danh sách yêu thích |
| `conversations` | Cuộc hội thoại chat |
| `messages` | Tin nhắn chat |
| `contacts` | Liên hệ |
| `coupons` | Mã giảm giá |

---

## 🔄 Changelog

### v2.1.0 (08/01/2026)
- ✅ Sửa lỗi toggle sidebar trên mobile
- ✅ Cải thiện giao diện nhân viên
- ✅ Thêm chức năng xóa cuộc hội thoại chat
- ✅ Sửa trang chi tiết đơn hàng admin
- ✅ Sửa chức năng lọc đánh giá theo số sao
- ✅ Cập nhật trang thống kê doanh thu
- ✅ Thêm hệ thống CSRF token
- ✅ Sửa lỗi form filter các trang admin
- ✅ Sửa lỗi thêm tài khoản mới

### v2.0.0 (08/01/2026)
- ✅ Thêm đăng nhập Google OAuth 2.0
- ✅ Thêm gửi email reset password qua SMTP
- ✅ Cải thiện giao diện trang quên mật khẩu
- ✅ Thêm trang đặt lại mật khẩu
- ✅ Sửa lỗi hiển thị avatar từ Google
- ✅ Sửa lỗi tìm kiếm sản phẩm
- ✅ Sửa lỗi xóa nhiều sản phẩm trong giỏ hàng
- ✅ Thêm nút xem trước sản phẩm (admin)
- ✅ Cập nhật thông báo lỗi đăng nhập

### v1.0.0 (01/12/2025)
- 🎉 Release đầu tiên

---

## 📝 License

MIT License - xem file [LICENSE](LICENSE) để biết thêm chi tiết.

---

## 👨‍💻 Tác giả

**Lê Duy**
- Email: leduytctv2019@gmail.com
- GitHub: [@leduy](https://github.com/leduy)

---

<p align="center">
  Made with ❤️ by TechShop Team
</p>
