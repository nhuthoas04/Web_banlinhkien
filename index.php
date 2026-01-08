<?php
/**
 * Main Router - TechShop
 * Xử lý routing chính cho website
 */

session_start();

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Load models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Review.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Chat.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/EmployeeController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// Get request URI
// First check if .htaccess passed the URL via query string
if (isset($_GET['url'])) {
    $path = trim($_GET['url'], '/');
} else {
    $request_uri = $_SERVER['REQUEST_URI'];
    $base_path = parse_url(BASE_URL, PHP_URL_PATH) ?: '/';
    $path = str_replace($base_path, '', $request_uri);
    $path = parse_url($path, PHP_URL_PATH) ?? '';
    $path = trim($path, '/');
}

// Get query parameters
$query_params = [];
if (isset($_SERVER['QUERY_STRING'])) {
    parse_str($_SERVER['QUERY_STRING'], $query_params);
}

// Initialize models
$db = Database::getInstance();
$userModel = new User();
$productModel = new Product();
$categoryModel = new Category();
$orderModel = new Order();
$reviewModel = new Review();
$cartModel = new Cart();
$chatModel = new Chat();

// Initialize controllers
$authController = new AuthController($userModel);
$userController = new UserController($productModel, $categoryModel, $orderModel, $reviewModel, $cartModel);
// Note: EmployeeController and AdminController will be initialized only when needed

// Check user authentication
$isLoggedIn = isset($_SESSION['user']);
$currentUser = $isLoggedIn ? $_SESSION['user'] : null;
$userRole = $currentUser['role'] ?? 'guest';

// Get cart count for logged in users
$cartCount = 0;
if ($isLoggedIn) {
    $cart = $cartModel->getByUserId($currentUser['id']);
    $cartCount = $cart ? count($cart['items'] ?? []) : 0;
}

// Get all categories for header
$categories = $categoryModel->getAll('active');

// Handle ?page= query string for user pages (backwards compatibility)
if (empty($path) && isset($query_params['page'])) {
    $pageName = $query_params['page'];
    // Map page names to paths
    $pageMapping = [
        'profile' => 'profile',
        'orders' => 'don-hang',
        'wishlist' => 'wishlist',
        'my-reviews' => 'my-reviews',
        'notifications' => 'notifications',
        'chat' => 'user-chat',
        'logout' => 'logout',
        'cart' => 'gio-hang',
        'checkout' => 'thanh-toan'
    ];
    
    if (isset($pageMapping[$pageName])) {
        $path = $pageMapping[$pageName];
    }
}

// Route handling
try {
    switch ($path) {
        // ==================== PUBLIC ROUTES ====================
        
        case '':
        case 'home':
            // Homepage
            $featuredProducts = $productModel->getFeatured(8);
            $newProducts = $productModel->getNew(8);
            $saleProducts = $productModel->getOnSale(8);
            $bestSellingProducts = $productModel->getTopSelling(8);
            $topCategories = array_slice($categories, 0, 6);
            include __DIR__ . '/views/user/home.php';
            break;
            
        case 'san-pham':
        case 'products':
            // Products listing
            $page = isset($query_params['p']) ? (int)$query_params['p'] : 1;
            $limit = 12;
            $category = null;
            $sort = $query_params['sort'] ?? 'newest';
            $minPrice = $query_params['min_price'] ?? null;
            $maxPrice = $query_params['max_price'] ?? null;
            
            // Filters array for Product model
            $filters = ['status' => 'active'];
            
            // Get category info if filtering by category
            if (!empty($query_params['category'])) {
                // Try to get by ID first, then by slug
                if (is_numeric($query_params['category'])) {
                    $category = $categoryModel->findById($query_params['category']);
                } else {
                    $category = $categoryModel->findBySlug($query_params['category']);
                }
                
                if ($category) {
                    $filters['category_id'] = $category['id'];
                }
            }
            
            // Brand filter (single)
            if (!empty($query_params['brand'])) {
                $filters['brand'] = $query_params['brand'];
            }
            
            // Brands filter (multiple)
            if (!empty($query_params['brands'])) {
                $filters['brands'] = $query_params['brands'];
            }
            
            // Rating filter
            if (!empty($query_params['rating'])) {
                $filters['rating'] = (int)$query_params['rating'];
            }
            
            // Price range filter
            if ($minPrice !== null) {
                $filters['min_price'] = (float)$minPrice;
            }
            if ($maxPrice !== null) {
                $filters['max_price'] = (float)$maxPrice;
            }
            
            // Search filter
            if (!empty($query_params['q'])) {
                $filters['search'] = $query_params['q'];
            }
            
            // Sort
            if ($sort) {
                $filters['sort'] = $sort;
            }
            
            // Get products
            $result = $productModel->getAll($page, $limit, $filters);
            $products = $result['products'] ?? [];
            $total = $result['total'] ?? 0;
            $totalPages = $result['totalPages'] ?? 1;
            
            // Get all categories for sidebar
            $categories = $categoryModel->getAll('active');
            
            // Get all brands for sidebar filter
            $brands = $productModel->getBrandsWithCount();
            
            include __DIR__ . '/views/user/products.php';
            break;
            
        case (preg_match('/^(san-pham|product)\/(.+)$/', $path, $matches) ? true : false):
            // Product detail
            $slug = $matches[2];
            $product = $productModel->getBySlug($slug);
            
            if (!$product) {
                http_response_code(404);
                include __DIR__ . '/views/errors/404.php';
                break;
            }
            
            // Increment view count
            $productModel->incrementViews($product['id']);
            
            // Get category
            $category = $categoryModel->findById($product['category_id']);
            
            // Get related data
            $reviews = $reviewModel->getByProductId($product['id']);
            $relatedProducts = $productModel->getRelated($product['category_id'], $product['id'], 4);
            
            // Calculate average rating
            $avgRating = 0;
            $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            if (!empty($reviews)) {
                $totalRating = 0;
                foreach ($reviews as $review) {
                    $totalRating += $review['rating'];
                    $ratingCounts[$review['rating']]++;
                }
                $avgRating = $totalRating / count($reviews);
            }
            
            include __DIR__ . '/views/user/product-detail.php';
            break;
            
        case 'danh-muc':
        case 'category':
            // Category page - redirect to products with filter
            $categorySlug = $query_params['slug'] ?? '';
            header('Location: ' . BASE_URL . 'san-pham?category=' . $categorySlug);
            break;
        
        case 'search':
        case 'tim-kiem':
            // Search page - redirect to products with search query
            $searchQuery = $query_params['q'] ?? '';
            header('Location: ' . BASE_URL . 'products?q=' . urlencode($searchQuery));
            exit;
            break;
            
        // ==================== AUTH ROUTES ====================
        
        case 'login':
        case 'dang-nhap':
            if ($isLoggedIn) {
                header('Location: ' . BASE_URL);
                exit;
            }
            
            // Kiểm tra đăng ký thành công
            if (isset($_GET['registered']) && $_GET['registered'] == '1') {
                flash('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->login($_POST);
                if ($result['success']) {
                    $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL;
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ' . $redirect);
                    exit;
                }
                $error = $result['message'];
            }
            
            include __DIR__ . '/views/auth/login.php';
            break;
            
        case 'register':
        case 'dang-ky':
            if ($isLoggedIn) {
                header('Location: ' . BASE_URL);
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->register($_POST);
                if ($result['success']) {
                    header('Location: ' . BASE_URL . 'login?registered=1');
                    exit;
                }
                $error = $result['message'];
            }
            
            include __DIR__ . '/views/auth/register.php';
            break;
            
        case 'logout':
        case 'dang-xuat':
            $authController->logout();
            header('Location: ' . BASE_URL);
            exit;
            break;
            
        case 'forgot-password':
        case 'quen-mat-khau':
            if ($isLoggedIn) {
                header('Location: ' . BASE_URL);
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $authController->forgotPassword($_POST['email']);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
            }
            
            include __DIR__ . '/views/auth/forgot-password.php';
            break;
            
        // ==================== USER ROUTES (Require Login) ====================
        
        case 'gio-hang':
        case 'cart':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . 'gio-hang';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $cart = $cartModel->getByUserId($currentUser['id']);
            $cartItems = $cart['items'] ?? [];
            $cartTotal = $cart['total'] ?? 0;
            
            include __DIR__ . '/views/user/cart.php';
            break;
            
        case 'thanh-toan':
        case 'checkout':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . 'thanh-toan';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $cart = $cartModel->getByUserId($currentUser['id']);
            
            if (!$cart || empty($cart['items'])) {
                header('Location: ' . BASE_URL . 'gio-hang');
                exit;
            }
            
            // Process checkout
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = $userController->processCheckout($currentUser['id'], $_POST);
                if ($result['success']) {
                    header('Location: ' . BASE_URL . 'don-hang/' . $result['order_id'] . '?success=1');
                    exit;
                }
                $error = $result['message'];
            }
            
            // Get cart items directly from Cart model (flat structure)
            $cartItems = $cart['items'];
            $cartTotal = $cart['total'];
            
            // Get user addresses
            $user = $userModel->getById($currentUser['id']);
            $addresses = $user['addresses'] ?? [];
            
            include __DIR__ . '/views/user/checkout.php';
            break;
            
        case 'don-hang':
        case 'orders':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . 'don-hang';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $statusFilter = $query_params['status'] ?? null;
            $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
            $orders = $orderModel->getByUserId($currentUser['id'], $statusFilter);
            $totalPages = 1; // Simple pagination - can be enhanced later
            
            include __DIR__ . '/views/user/orders.php';
            break;
            
        case (preg_match('/^(don-hang|order)\/(.+)$/', $path, $matches) ? true : false):
            // Order detail
            if (!$isLoggedIn) {
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $orderId = $matches[2];
            $order = $orderModel->getById($orderId);
            
            if (!$order || (string)$order['user_id'] !== (string)$currentUser['id']) {
                http_response_code(404);
                include __DIR__ . '/views/errors/404.php';
                break;
            }
            
            include __DIR__ . '/views/user/order-detail.php';
            break;
            
        case 'tai-khoan':
        case 'profile':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . 'tai-khoan';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $action = $_POST['action'] ?? 'update_profile';
                
                switch ($action) {
                    case 'update_profile':
                        $result = $userController->updateProfile($currentUser['id'], $_POST);
                        break;
                    case 'change_password':
                        $result = $userController->changePassword($currentUser['id'], $_POST);
                        break;
                    case 'add_address':
                        $result = $userController->addAddress($currentUser['id'], $_POST);
                        break;
                    case 'delete_address':
                        $result = $userController->deleteAddress($currentUser['id'], $_POST['address_id']);
                        break;
                }
                
                if (isset($result)) {
                    $message = $result['message'];
                    $messageType = $result['success'] ? 'success' : 'error';
                    
                    // Update session if profile updated
                    if ($result['success'] && $action === 'update_profile') {
                        $_SESSION['user'] = array_merge($_SESSION['user'], $_POST);
                    }
                }
            }
            
            $user = $userModel->getById($currentUser['id']);
            $orderStats = $orderModel->getUserStats($currentUser['id']);
            
            include __DIR__ . '/views/user/profile.php';
            break;
        
        case 'wishlist':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . '?page=wishlist';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            // Get wishlist items (placeholder - show empty for now)
            $wishlistItems = [];
            $user = $userModel->getById($currentUser['id']);
            $currentPage = 'wishlist';
            
            // Simple wishlist view
            $pageTitle = 'Sản phẩm yêu thích';
            include __DIR__ . '/views/user/wishlist.php';
            break;
            
        case 'my-reviews':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . '?page=my-reviews';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            // Get user's reviews
            $user = $userModel->getById($currentUser['id']);
            $userReviews = $reviewModel->getByUserId($currentUser['id']);
            $currentPage = 'reviews';
            
            $pageTitle = 'Đánh giá của tôi';
            include __DIR__ . '/views/user/my-reviews.php';
            break;
            
        case 'notifications':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . '?page=notifications';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $user = $userModel->getById($currentUser['id']);
            $notifications = []; // Placeholder
            $currentPage = 'notifications';
            
            $pageTitle = 'Thông báo';
            include __DIR__ . '/views/user/notifications.php';
            break;
            
        case 'user-chat':
            if (!$isLoggedIn) {
                $_SESSION['redirect_after_login'] = BASE_URL . '?page=chat';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $user = $userModel->getById($currentUser['id']);
            $conversation = $chatModel->getOrCreateConversation($currentUser['id'], $user['name'] ?? 'Khách hàng');
            $messages = $chatModel->getMessages($conversation['id']);
            $currentPage = 'chat';
            
            $pageTitle = 'Hỗ trợ trực tuyến';
            include __DIR__ . '/views/user/chat.php';
            break;
            
        // ==================== EMPLOYEE ROUTES ====================
        
        case 'employee':
            if (!$isLoggedIn || !in_array($userRole, ['employee', 'admin'])) {
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            $page = $query_params['page'] ?? 'dashboard';
            
            switch ($page) {
                case 'dashboard':
                    $todayOrders = $orderModel->getTodayCount();
                    $pendingOrders = $orderModel->getCountByStatus('pending');
                    $pendingChats = $chatModel->getPendingCount();
                    $pendingReviews = $reviewModel->getPendingCount();
                    $recentPendingOrders = $orderModel->getRecent('pending', 5);
                    $recentChats = $chatModel->getRecent(5);
                    $recentReviews = $reviewModel->getRecent(5);
                    
                    include __DIR__ . '/views/employee/dashboard.php';
                    break;
                    
                case 'orders':
                    $statusFilter = $query_params['status'] ?? '';
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    
                    $filters = [];
                    if ($statusFilter) $filters['status'] = $statusFilter;
                    
                    $result = $orderModel->getAll($currentPage, $limit, $filters);
                    $orders = $result['data'];
                    $totalOrders = $result['total'];
                    $totalPages = $result['total_pages'];
                    $orderCounts = $orderModel->getCountsByStatus();
                    
                    include __DIR__ . '/views/employee/orders.php';
                    break;
                    
                case 'reviews':
                    $statusFilter = $query_params['status'] ?? '';
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    
                    $filters = [];
                    if ($statusFilter) $filters['status'] = $statusFilter;
                    
                    $result = $reviewModel->getAll($currentPage, $limit, $filters);
                    $reviews = $result['data'];
                    $totalReviews = $result['total'];
                    $totalPages = $result['total_pages'];
                    $reviewCounts = $reviewModel->getCountsByStatus();
                    
                    include __DIR__ . '/views/employee/reviews.php';
                    break;
                    
                case 'chat':
                    $conversations = $chatModel->getAllConversations();
                    $activeConversation = null;
                    $messages = [];
                    
                    if (isset($query_params['id'])) {
                        $activeConversation = $chatModel->getConversation($query_params['id']);
                        $messages = $chatModel->getMessages($query_params['id']);
                        
                        // Mark as read
                        $chatModel->markAsRead($query_params['id']);
                        
                        // Get user stats
                        if ($activeConversation) {
                            $userId = $activeConversation['user_id'];
                            $userStats = $orderModel->getUserStats($userId);
                            $userRecentOrders = $orderModel->getByUserId($userId, null, 5);
                        }
                    }
                    
                    include __DIR__ . '/views/employee/chat.php';
                    break;
                
                case 'profile':
                    // Employee profile
                    $user = $userModel->getById($currentUser['id']);
                    $orderStats = $orderModel->getUserStats($currentUser['id']);
                    include __DIR__ . '/views/user/profile.php';
                    break;
                    
                default:
                    http_response_code(404);
                    include __DIR__ . '/views/errors/404.php';
            }
            break;
            
        // ==================== ADMIN ROUTES ====================
        
        case 'admin':
            if (!$isLoggedIn || $userRole !== 'admin') {
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
            
            // Initialize AdminController
            $adminController = new AdminController();
            
            $page = $query_params['page'] ?? 'dashboard';
            
            switch ($page) {
                case 'dashboard':
                    $stats = $adminController->getDashboardStats();
                    $recentOrders = $orderModel->getRecent(null, 10);
                    $topProducts = $productModel->getTopSelling(5);
                    $lowStockProducts = $productModel->getLowStock(5);
                    $recentReviews = $reviewModel->getRecent(5);
                    $revenueData = $orderModel->getRevenueByDays(7);
                    $orderStatusData = $orderModel->getCountsByStatus();
                    
                    include __DIR__ . '/views/admin/dashboard.php';
                    break;
                    
                case 'products':
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    $search = $query_params['search'] ?? null;
                    $categoryFilter = $query_params['category'] ?? null;
                    $statusFilter = $query_params['status'] ?? null;
                    $brandFilter = $query_params['brand'] ?? null;
                    
                    $result = $productModel->getAllAdmin($search, $categoryFilter, $statusFilter, $currentPage, $limit);
                    $products = $result['data'] ?? $result['products'] ?? [];
                    $totalProducts = $result['total'] ?? 0;
                    $totalPages = ceil($totalProducts / $limit);
                    $brands = $productModel->getBrands();
                    $categories = $categoryModel->getForDropdown();
                    
                    include __DIR__ . '/views/admin/products.php';
                    break;
                    
                case 'product-add':
                case 'product-edit':
                    $isEdit = $page === 'product-edit';
                    $product = null;
                    
                    if ($isEdit && isset($query_params['id'])) {
                        $product = $productModel->getById($query_params['id']);
                        if (!$product) {
                            header('Location: ' . BASE_URL . 'admin?page=products');
                            exit;
                        }
                    }
                    
                    // Get categories for dropdown
                    $categories = $categoryModel->getForDropdown();
                    
                    include __DIR__ . '/views/admin/product-form.php';
                    break;
                    
                case 'orders':
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    $statusFilter = $query_params['status'] ?? '';
                    $search = $query_params['search'] ?? '';
                    
                    $filters = [];
                    if ($statusFilter) $filters['status'] = $statusFilter;
                    if ($search) $filters['search'] = $search;
                    
                    $result = $orderModel->getAll($currentPage, $limit, $filters);
                    $orders = $result['data'];
                    $totalOrders = $result['total'];
                    $totalPages = $result['total_pages'];
                    $orderCounts = $orderModel->getCountsByStatus();
                    
                    include __DIR__ . '/views/admin/orders.php';
                    break;
                    
                case 'users':
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    $search = $query_params['search'] ?? '';
                    $roleFilter = $query_params['role'] ?? '';
                    $statusFilter = $query_params['status'] ?? '';
                    
                    $filters = [];
                    if ($search) $filters['search'] = $search;
                    if ($roleFilter) $filters['role'] = $roleFilter;
                    if ($statusFilter) $filters['status'] = $statusFilter;
                    
                    $result = $userModel->getAll($currentPage, $limit, $filters);
                    $users = $result['data'];
                    $totalUsers = $result['total'];
                    $totalPages = $result['total_pages'];
                    $userStats = $userModel->getCountsByRole();
                    
                    include __DIR__ . '/views/admin/users.php';
                    break;
                    
                case 'categories':
                    $categories = $categoryModel->getAllWithProductCount();
                    include __DIR__ . '/views/admin/categories.php';
                    break;
                    
                case 'reviews':
                    $currentPage = isset($query_params['p']) ? (int)$query_params['p'] : 1;
                    $limit = 20;
                    $statusFilter = $query_params['status'] ?? null;
                    $ratingFilter = $query_params['rating'] ?? null;
                    
                    $result = $reviewModel->getAllAdmin($statusFilter, $ratingFilter, $currentPage, $limit);
                    $reviews = $result['reviews'];
                    $totalReviews = $result['total'];
                    $totalPages = ceil($totalReviews / $limit);
                    $reviewStats = $reviewModel->getStatsByRating();
                    $avgRating = $reviewModel->getAverageRating();
                    
                    include __DIR__ . '/views/admin/reviews.php';
                    break;
                
                case 'revenue':
                    $stats = $adminController->getDashboardStats();
                    $revenueData = $orderModel->getRevenueByDays(30);
                    $orderStats = $orderModel->getCountsByStatus();
                    $topProducts = $productModel->getTopSelling(10);
                    $topCustomers = $userModel->getTopCustomers(10);
                    
                    include __DIR__ . '/views/admin/revenue.php';
                    break;
                
                case 'chats':
                    require_once __DIR__ . '/models/Conversation.php';
                    $conversationModel = new Conversation();
                    
                    $conversations = $conversationModel->getAll(1, 50, [])['data'] ?? [];
                    $activeConversation = null;
                    $messages = [];
                    $userStats = [];
                    
                    if (isset($query_params['conv'])) {
                        $activeConversation = $conversationModel->getById($query_params['conv']);
                        if ($activeConversation) {
                            $messages = $chatModel->getMessages($activeConversation['id']);
                            $userId = $activeConversation['user_id'];
                            $userStats = $orderModel->getUserStats($userId);
                        }
                    }
                    
                    include __DIR__ . '/views/admin/chats.php';
                    break;
                
                case 'profile':
                    // Admin/Employee profile - redirect to main profile page
                    $user = $userModel->getById($currentUser['id']);
                    $orderStats = $orderModel->getUserStats($currentUser['id']);
                    include __DIR__ . '/views/user/profile.php';
                    break;
                    
                default:
                    http_response_code(404);
                    include __DIR__ . '/views/errors/404.php';
            }
            break;
            
        // ==================== STATIC PAGES ====================
        
        case 'gioi-thieu':
        case 'about':
            include __DIR__ . '/views/pages/about.php';
            break;
            
        case 'lien-he':
        case 'contact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Process contact form
                $result = $userController->processContact($_POST);
                $message = $result['message'];
                $messageType = $result['success'] ? 'success' : 'error';
            }
            include __DIR__ . '/views/pages/contact.php';
            break;
            
        case 'chinh-sach-bao-hanh':
            include __DIR__ . '/views/pages/warranty.php';
            break;
            
        case 'chinh-sach-doi-tra':
            include __DIR__ . '/views/pages/return-policy.php';
            break;
            
        case 'huong-dan-mua-hang':
            include __DIR__ . '/views/pages/guide.php';
            break;
            
        // ==================== 404 NOT FOUND ====================
        
        default:
            http_response_code(404);
            include __DIR__ . '/views/errors/404.php';
            break;
    }
    
} catch (Exception $e) {
    // Log error
    error_log('Router Error: ' . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    if (DEBUG_MODE) {
        echo '<h1>Error</h1><pre>' . $e->getMessage() . '</pre>';
    } else {
        include __DIR__ . '/views/errors/500.php';
    }
}

