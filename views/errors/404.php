<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang | TechShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --dark: #1d3557;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .error-container {
            text-align: center;
            padding: 40px;
        }
        
        .error-code {
            font-size: 150px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            text-shadow: 4px 4px 0 rgba(230, 57, 70, 0.2);
        }
        
        .error-title {
            font-size: 32px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
        }
        
        .error-message {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .error-illustration {
            max-width: 400px;
            margin: 30px auto;
        }
        
        .error-illustration svg {
            width: 100%;
            height: auto;
        }
        
        .btn-home {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        
        .btn-home:hover {
            background: #d62839;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(230, 57, 70, 0.3);
        }
        
        .search-box {
            max-width: 400px;
            margin: 30px auto 0;
        }
        
        .search-box .input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-box input {
            border: none;
            padding: 15px 20px;
        }
        
        .search-box input:focus {
            box-shadow: none;
        }
        
        .search-box button {
            background: var(--primary);
            border: none;
            padding: 15px 25px;
            color: white;
        }
        
        .search-box button:hover {
            background: #d62839;
        }
        
        .popular-links {
            margin-top: 40px;
        }
        
        .popular-links h5 {
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .popular-links a {
            color: #6c757d;
            text-decoration: none;
            padding: 5px 15px;
            border-radius: 20px;
            background: white;
            margin: 5px;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .popular-links a:hover {
            background: var(--primary);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h1 class="error-title">Oops! Trang không tồn tại</h1>
        <p class="error-message">
            Trang bạn đang tìm kiếm có thể đã bị xóa, đổi tên hoặc tạm thời không khả dụng.
        </p>
        
        <div class="error-illustration">
            <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="50" y="100" width="300" height="150" rx="10" fill="#f8f9fa" stroke="#dee2e6" stroke-width="2"/>
                <rect x="70" y="120" width="120" height="10" rx="5" fill="#e9ecef"/>
                <rect x="70" y="140" width="80" height="10" rx="5" fill="#e9ecef"/>
                <rect x="70" y="160" width="100" height="10" rx="5" fill="#e9ecef"/>
                <circle cx="300" cy="175" r="40" fill="#e63946" opacity="0.2"/>
                <path d="M285 160 L315 190 M315 160 L285 190" stroke="#e63946" stroke-width="4" stroke-linecap="round"/>
                <circle cx="350" cy="80" r="20" fill="#ffc107" opacity="0.3"/>
                <circle cx="50" cy="200" r="15" fill="#28a745" opacity="0.3"/>
            </svg>
        </div>
        
        <a href="/" class="btn-home">
            <i class="fas fa-home"></i>
            Về trang chủ
        </a>
        
        <div class="search-box">
            <form action="/products" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                    <button type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="popular-links">
            <h5>Có thể bạn quan tâm:</h5>
            <a href="/products?category=laptop">Laptop</a>
            <a href="/products?category=pc">PC Gaming</a>
            <a href="/products?category=monitor">Màn hình</a>
            <a href="/products?category=accessories">Phụ kiện</a>
            <a href="/contact">Liên hệ</a>
        </div>
    </div>
</body>
</html>


