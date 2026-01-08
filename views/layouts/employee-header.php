<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="base-url" content="<?= BASE_URL ?>">
    <meta name="theme-color" content="#059669">
    <meta name="format-detection" content="telephone=no">
    <title><?= $pageTitle ?? 'Nhân viên' ?> - <?= SITE_NAME ?></title>
    
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
    <link href="<?= ASSETS_URL ?>/css/admin.css?v=20260109002" rel="stylesheet">
    
    <style>
        /* Employee theme - Using same DARK colors as Admin */
        :root {
            --employee-primary: #1e293b;
            --employee-primary-light: #334155;
            --employee-primary-dark: #0f172a;
        }
        
        .admin-sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        }
        
        .sidebar-logo {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-logo i {
            color: #60a5fa;
        }
        
        .sidebar-avatar {
            border-color: #60a5fa;
        }
        
        .menu-item.active a::before {
            background: #60a5fa;
        }
        
        .menu-item.active a,
        .menu-item a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .menu-badge {
            background: #f59e0b;
        }
        
        .employee-badge {
            background: #60a5fa !important;
            color: #fff !important;
        }
        
        .employee-stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        
        .employee-stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .employee-stat-card.orders .icon {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
        }
        
        .employee-stat-card.pending .icon {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }
        
        .employee-stat-card.chats .icon {
            background: rgba(99, 102, 241, 0.15);
            color: #6366f1;
        }
        
        .employee-stat-card.reviews .icon {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }
        
        .employee-stat-card .info h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #1e293b;
        }
        
        .employee-stat-card .info span {
            color: #64748b;
            font-size: 13px;
        }
        
        .employee-stat-card .trend {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 50px;
        }
        
        .employee-stat-card .trend.up {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }
        
        .employee-stat-card .trend.down {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
        
        /* Employee buttons */
        .btn-employee-primary {
            background: linear-gradient(135deg, var(--employee-primary) 0%, var(--employee-primary-light) 100%);
            border: none;
            color: #fff;
        }
        
        .btn-employee-primary:hover {
            background: linear-gradient(135deg, var(--employee-primary-dark) 0%, var(--employee-primary) 100%);
            color: #fff;
        }
    </style>
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
                <h6><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Nhân viên') ?></h6>
                <span class="badge employee-badge">
                    <i class="fas fa-headset me-1"></i>Nhân viên
                </span>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <?php $currentPage = $_GET['page'] ?? 'dashboard'; ?>
                
                <li class="menu-header">CÔNG VIỆC</li>
                
                <li class="menu-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>employee?page=dashboard">
                        <i class="fas fa-chart-line"></i>
                        <span>Tổng quan</span>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'orders' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>employee?page=orders">
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
                    <a href="<?= BASE_URL ?>employee?page=reviews">
                        <i class="fas fa-star"></i>
                        <span>Duyệt đánh giá</span>
                        <?php 
                        $reviewModel = new Review();
                        $pendingReviews = $reviewModel->getStatistics()['pending_reviews'] ?? 0;
                        if ($pendingReviews > 0):
                        ?>
                        <span class="menu-badge"><?= $pendingReviews ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                
                <li class="menu-item <?= $currentPage === 'chat' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>employee?page=chat">
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
