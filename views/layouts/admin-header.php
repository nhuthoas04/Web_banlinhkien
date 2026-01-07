<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <meta name="theme-color" content="#4f46e5">
    <meta name="format-detection" content="telephone=no">
    <title><?= $pageTitle ?? 'Quản trị' ?> - <?= SITE_NAME ?></title>
    
    <!-- Google Fonts - Vietnamese support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Admin CSS -->
    <link href="<?= ASSETS_URL ?>/css/admin.css?v=20260106004" rel="stylesheet">
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="<?= BASE_URL ?>" class="sidebar-logo">
                <i class="fas fa-microchip"></i>
                <span>TechShop</span>
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-user">
            <img src="<?= BASE_URL ?>assets/images/default-avatar.svg" alt="Avatar" class="sidebar-avatar">
            <div class="sidebar-user-info">
                <h6><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></h6>
                <?php $userRole = $_SESSION['role'] ?? $_SESSION['user']['role'] ?? 'user'; ?>
                <span class="badge bg-<?= $userRole === ROLE_ADMIN ? 'danger' : 'primary' ?>">
                    <?= $userRole === ROLE_ADMIN ? 'Admin' : 'Nhân viên' ?>
                </span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <?php 
                $currentPage = $_GET['page'] ?? 'dashboard';
                $prefix = isAdmin() ? 'admin' : 'employee';
                ?>
                
                <li class="menu-header">TỔNG QUAN</li>
                <li class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?><?= $prefix ?>?page=dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-header">QUẢN LÝ</li>
                
                <?php if (isAdmin()): ?>
                <li class="menu-item <?= $currentPage === 'revenue' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>admin?page=revenue">
                        <i class="fas fa-chart-line"></i>
                        <span>Doanh thu</span>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'users' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>admin?page=users">
                        <i class="fas fa-users"></i>
                        <span>Tài khoản</span>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'categories' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>admin?page=categories">
                        <i class="fas fa-folder-tree"></i>
                        <span>Danh mục</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="menu-item <?= $currentPage === 'products' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?><?= $prefix ?>?page=products">
                        <i class="fas fa-boxes-stacked"></i>
                        <span>Sản phẩm</span>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'orders' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?><?= $prefix ?>?page=orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Đơn hàng</span>
                        <?php 
                        $orderModel = new Order();
                        $pendingCount = $orderModel->getStatistics()['status_counts'][ORDER_PENDING] ?? 0;
                        if ($pendingCount > 0):
                        ?>
                        <span class="menu-badge"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'reviews' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?><?= $prefix ?>?page=reviews">
                        <i class="fas fa-star"></i>
                        <span>Đánh giá</span>
                        <?php 
                        $reviewModel = new Review();
                        $pendingReviews = $reviewModel->getStatistics()['pending_reviews'] ?? 0;
                        if ($pendingReviews > 0):
                        ?>
                        <span class="menu-badge"><?= $pendingReviews ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'chats' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?><?= $prefix ?>?page=chats">
                        <i class="fas fa-comments"></i>
                        <span>Chat hỗ trợ</span>
                        <?php 
                        $chatModel = new Chat();
                        $pendingChats = $chatModel->countPending();
                        if ($pendingChats > 0):
                        ?>
                        <span class="menu-badge"><?= $pendingChats ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="menu-header">CÁ NHÂN</li>
                <li class="menu-item <?= $currentPage === 'profile' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>profile">
                        <i class="fas fa-user-circle"></i>
                        <span>Thông tin cá nhân</span>
                    </a>
                </li>
                
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>">
                        <i class="fas fa-store"></i>
                        <span>Về trang chủ</span>
                    </a>
                </li>
                
                <li class="menu-item">
                    <a href="<?= BASE_URL ?>logout" class="text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?><?= $prefix ?>/dashboard">Dashboard</a></li>
                        <?php if (isset($breadcrumb)): ?>
                            <?php foreach ($breadcrumb as $item): ?>
                            <li class="breadcrumb-item <?= isset($item['active']) ? 'active' : '' ?>">
                                <?php if (isset($item['url'])): ?>
                                    <a href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                                <?php else: ?>
                                    <?= $item['title'] ?>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
            
            <div class="header-right">
                <!-- Search -->
                <div class="header-search">
                    <input type="text" placeholder="Tìm kiếm..." class="form-control">
                    <i class="fas fa-search"></i>
                </div>
                
                <!-- Notifications -->
                <div class="dropdown header-dropdown">
                    <button class="btn header-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notification-menu">
                        <div class="notification-header">
                            <h6>Thông báo</h6>
                            <a href="#">Đánh dấu đã đọc</a>
                        </div>
                        <div class="notification-body">
                            <a href="#" class="notification-item unread">
                                <div class="notification-icon bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="notification-content">
                                    <p>Đơn hàng mới #ORD12345</p>
                                    <span>2 phút trước</span>
                                </div>
                            </a>
                            <a href="#" class="notification-item">
                                <div class="notification-icon bg-success">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="notification-content">
                                    <p>Người dùng mới đăng ký</p>
                                    <span>10 phút trước</span>
                                </div>
                            </a>
                        </div>
                        <div class="notification-footer">
                            <a href="#">Xem tất cả thông báo</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="dropdown header-dropdown">
                    <button class="btn header-user-btn" data-bs-toggle="dropdown">
                        <img src="<?= BASE_URL ?>assets/images/default-avatar.svg" alt="Avatar">
                        <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?= BASE_URL ?>profile">
                            <i class="fas fa-user"></i> Thông tin cá nhân
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>">
                            <i class="fas fa-store"></i> Về trang chủ
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="admin-content">
            <!-- Flash Messages -->
            <?php if ($successMsg = flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $successMsg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if ($errorMsg = flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $errorMsg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>


