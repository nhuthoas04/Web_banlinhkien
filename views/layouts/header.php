<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <meta name="theme-color" content="#2563eb">
    <meta name="format-detection" content="telephone=no">
    <title><?= $pageTitle ?? SITE_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= ASSETS_URL ?>/images/favicon.png">
    
    <!-- Google Fonts - Vietnamese support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 - Multiple CDN sources -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
    
    <!-- Swiper CSS -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= ASSETS_URL ?>/css/style.css?v=<?= time() ?>" rel="stylesheet">
    
    <?php if (isset($extraCss)): ?>
        <?php foreach ($extraCss as $css): ?>
            <link href="<?= ASSETS_URL ?>/css/<?= $css ?>?v=<?= time() ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header header-techstore">
        <!-- Main Header -->
        <nav class="navbar navbar-expand-lg main-nav-techstore">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand logo-brand" href="<?= BASE_URL ?>">
                    <div class="logo-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div class="logo-info">
                        <span class="logo-name">TechStore</span>
                        <span class="logo-desc">Linh kiện máy tính</span>
                    </div>
                </a>
                
                <!-- Category Button -->
                <div class="category-dropdown dropdown d-none d-lg-block">
                    <button class="btn-category dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                        <span>Danh mục</span>
                    </button>
                    <ul class="dropdown-menu category-menu">
                        <?php 
                        $categoryModel = new Category();
                        $navCategories = $categoryModel->getParentCategories();
                        foreach ($navCategories as $cat): 
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>products?category=<?= $cat['id'] ?>">
                                <i class="<?= $cat['icon'] ?? 'fas fa-folder' ?>"></i> <?= $cat['name'] ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Search Bar -->
                <div class="search-bar-techstore d-none d-lg-block">
                    <form action="<?= BASE_URL ?>search" method="GET" class="search-form-techstore">
                        <i class="fas fa-search search-icon-left"></i>
                        <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." class="search-input-techstore" value="<?= $_GET['q'] ?? '' ?>">
                    </form>
                </div>
                
                <!-- Hotline -->
                <div class="hotline-box d-none d-lg-flex">
                    <i class="fas fa-phone-alt"></i>
                    <span>Hotline: <?= SITE_PHONE ?></span>
                </div>
                
                <!-- Cart - Always visible -->
                <a class="cart-link-techstore d-none d-lg-flex" href="<?= BASE_URL ?>cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count-techstore" id="cart-count">
                        <?php
                        $cartCount = 0;
                        if (isLoggedIn()) {
                            $cartModel = new Cart();
                            $cartCount = $cartModel->getCount($_SESSION['user_id']);
                        } else {
                            $cartCount = count($_SESSION['cart'] ?? []);
                        }
                        echo $cartCount;
                        ?>
                    </span>
                </a>
                
                <!-- Login/User Button - Always visible on desktop -->
                <?php if (isLoggedIn()): ?>
                <div class="dropdown user-dropdown-techstore d-none d-lg-block">
                    <a class="dropdown-toggle user-btn-techstore" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-menu-techstore">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>profile"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>don-hang"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                        <?php if (isEmployee()): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>employee?page=dashboard"><i class="fas fa-chart-line"></i> Trang nhân viên</a></li>
                        <?php endif; ?>
                        <?php if (isAdmin()): ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>admin?page=dashboard"><i class="fas fa-cog"></i> Quản trị</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a class="btn-login-techstore d-none d-lg-flex" href="<?= BASE_URL ?>login">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </a>
                <?php endif; ?>
                
                <!-- Mobile Toggle -->
                <button class="navbar-toggler navbar-toggler-techstore d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Mobile Nav Items -->
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav ms-auto nav-right-techstore d-lg-none">
                        <!-- Mobile Category -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-bars"></i> Danh mục
                            </a>
                            <ul class="dropdown-menu category-menu">
                                <?php foreach ($navCategories as $cat): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>products?category=<?= $cat['id'] ?>">
                                        <i class="<?= $cat['icon'] ?? 'fas fa-folder' ?>"></i> <?= $cat['name'] ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        
                        <!-- Mobile Cart -->
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= BASE_URL ?>cart">
                                <i class="fas fa-shopping-cart"></i> Giỏ hàng
                                <span class="badge bg-success"><?= $cartCount ?></span>
                            </a>
                        </li>
                        
                        <!-- Mobile User Menu -->
                        <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-menu-techstore">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>profile"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>don-hang"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                                <?php if (isEmployee()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>employee?page=dashboard"><i class="fas fa-chart-line"></i> Trang nhân viên</a></li>
                                <?php endif; ?>
                                <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>admin?page=dashboard"><i class="fas fa-cog"></i> Quản trị</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="btn-login-techstore" href="<?= BASE_URL ?>login">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Mobile Search -->
        <div class="mobile-search-techstore d-lg-none">
            <div class="container">
                <form action="<?= BASE_URL ?>search" method="GET" class="search-form-techstore mobile">
                    <i class="fas fa-search search-icon-left"></i>
                    <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." class="search-input-techstore">
                </form>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php if ($successMsg = flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-check-circle"></i> <?= $successMsg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($errorMsg = flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= $errorMsg ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content">



