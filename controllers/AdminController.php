<?php
/**
 * Admin Controller
 * Xử lý các chức năng của Admin
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Chat.php';

class AdminController {
    private $userModel;
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $reviewModel;
    private $chatModel;
    
    public function __construct() {
        // Kiểm tra quyền Admin
        if (!isAdmin()) {
            flash('error', 'Bạn không có quyền truy cập');
            redirect('/login');
        }
        
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->reviewModel = new Review();
        $this->chatModel = new Chat();
    }
    
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats() {
        return [
            'users' => $this->userModel->getStatistics(),
            'products' => $this->productModel->getStatistics(),
            'orders' => $this->orderModel->getStatistics(),
            'reviews' => $this->reviewModel->getStatistics()
        ];
    }
    
    /**
     * Dashboard Admin
     */
    public function dashboard() {
        // Thống kê tổng quan
        $userStats = $this->userModel->getStatistics();
        $productStats = $this->productModel->getStatistics();
        $orderStats = $this->orderModel->getStatistics();
        $reviewStats = $this->reviewModel->getStatistics();
        $chatStats = $this->chatModel->getStatistics();
        
        // Doanh thu theo tháng
        $revenueByMonth = $this->orderModel->getRevenueByMonth(12);
        
        // Đơn hàng gần đây (null = all statuses, 10 = limit)
        $recentOrders = $this->orderModel->getRecent(null, 10);
        
        // Doanh thu theo ngày (30 ngày)
        $revenueByDay = $this->orderModel->getRevenueByDay(30);
        
        // Thống kê tổng hợp cho view
        $stats = [
            'total_orders' => $orderStats['total'] ?? 0,
            'order_change' => $orderStats['change'] ?? 0,
            'total_revenue' => $orderStats['revenue'] ?? 0,
            'revenue_change' => $orderStats['revenue_change'] ?? 0,
            'total_users' => $userStats['total'] ?? 0,
            'user_change' => $userStats['change'] ?? 0,
            'total_products' => $productStats['total'] ?? 0,
            'product_change' => $productStats['change'] ?? 0
        ];
        
        include __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Thông tin cá nhân Admin
     */
    public function profile() {
        $user = $this->userModel->getById($_SESSION['user_id']);
        include __DIR__ . '/../views/admin/profile.php';
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile() {
        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];
        
        if (!empty($_FILES['avatar']['name'])) {
            $uploadResult = uploadImage($_FILES['avatar'], 'avatars');
            if ($uploadResult['success']) {
                $data['avatar'] = $uploadResult['path'];
            }
        }
        
        $result = $this->userModel->updateProfile($_SESSION['user_id'], $data);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ DOANH THU ========== */
    
    /**
     * Trang thống kê doanh thu
     */
    public function revenue() {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        
        $orderStats = $this->orderModel->getStatistics($startDate, $endDate . ' 23:59:59');
        $revenueByDay = $this->orderModel->getRevenueByDay(30);
        $revenueByMonth = $this->orderModel->getRevenueByMonth(12);
        
        // Top sản phẩm bán chạy
        $topProducts = $this->productModel->getBestSelling(10);
        
        include __DIR__ . '/../views/admin/revenue.php';
    }
    
    /* ========== QUẢN LÝ TÀI KHOẢN ========== */
    
    /**
     * Danh sách tài khoản
     */
    public function users() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $role = $_GET['role'] ?? null;
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $filter = [];
        if ($role) {
            $filter['role'] = $role;
        }
        if ($status) {
            $filter['status'] = $status;
        }
        if ($search) {
            $filter['$or'] = [
                ['fullname' => ['$regex' => $search, '$options' => 'i']],
                ['email' => ['$regex' => $search, '$options' => 'i']],
                ['phone' => ['$regex' => $search, '$options' => 'i']]
            ];
        }
        
        $result = $this->userModel->getAllUsers($filter, $page);
        
        include __DIR__ . '/../views/admin/users.php';
    }
    
    /**
     * Chi tiết tài khoản
     */
    public function userDetail($userId) {
        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            flash('error', 'Tài khoản không tồn tại');
            redirect('/admin/users');
        }
        
        // Lấy đơn hàng của user
        $orders = $this->orderModel->getByUser($userId, null, 1, 10);
        
        include __DIR__ . '/../views/admin/user-detail.php';
    }
    
    /**
     * Thêm tài khoản mới
     */
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../views/admin/user-form.php';
            return;
        }
        
        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? '',
            'address' => $_POST['address'] ?? '',
            'role' => $_POST['role'] ?? ROLE_USER
        ];
        
        // Upload avatar nếu có
        if (!empty($_FILES['avatar']['name'])) {
            $uploadResult = uploadImage($_FILES['avatar'], 'avatars');
            if ($uploadResult['success']) {
                $data['avatar'] = $uploadResult['path'];
            }
        }
        
        $result = $this->userModel->register($data);
        
        if ($result['success'] && $data['role'] !== ROLE_USER) {
            $this->userModel->changeRole($result['user_id'], $data['role']);
        }
        
        echo json_encode($result);
    }
    
    /**
     * Khóa/Mở khóa tài khoản
     */
    public function toggleUserStatus() {
        $userId = $_POST['user_id'] ?? '';
        $result = $this->userModel->toggleStatus($userId);
        echo json_encode($result);
    }
    
    /**
     * Thay đổi quyền tài khoản
     */
    public function changeUserRole() {
        $userId = $_POST['user_id'] ?? '';
        $role = $_POST['role'] ?? '';
        $result = $this->userModel->changeRole($userId, $role);
        echo json_encode($result);
    }
    
    /**
     * Xóa tài khoản
     */
    public function deleteUser() {
        $userId = $_POST['user_id'] ?? '';
        
        if ($userId === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa tài khoản của chính mình']);
            return;
        }
        
        $result = $this->userModel->delete($userId);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ SẢN PHẨM ========== */
    
    /**
     * Danh sách sản phẩm
     */
    public function products() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $category = $_GET['category'] ?? null;
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $filter = [];
        if ($category) {
            $filter['category_id'] = $category;
        }
        if ($status) {
            $filter['status'] = $status;
        }
        
        if ($search) {
            $result = $this->productModel->search($search, $page, ADMIN_ITEMS_PER_PAGE);
        } else {
            $result = $this->productModel->getAll($filter, ['created_at' => -1], $page, ADMIN_ITEMS_PER_PAGE);
        }
        
        $categories = $this->categoryModel->getAll();
        
        include __DIR__ . '/../views/admin/products.php';
    }
    
    /**
     * Thêm sản phẩm
     */
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $this->categoryModel->getAll(['status' => 'active']);
            include __DIR__ . '/../views/admin/product-form.php';
            return;
        }
        
        // Upload thumbnail
        $thumbnail = 'default-product.png';
        if (!empty($_FILES['thumbnail']['name'])) {
            $uploadResult = uploadImage($_FILES['thumbnail'], 'products');
            if ($uploadResult['success']) {
                $thumbnail = $uploadResult['path'];
            }
        }
        
        // Upload images
        $images = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $tmpName,
                    'size' => $_FILES['images']['size'][$key]
                ];
                $uploadResult = uploadImage($file, 'products');
                if ($uploadResult['success']) {
                    $images[] = $uploadResult['path'];
                }
            }
        }
        
        // Xử lý specifications
        $specifications = [];
        if (!empty($_POST['spec_names'])) {
            foreach ($_POST['spec_names'] as $key => $name) {
                if (!empty($name) && !empty($_POST['spec_values'][$key])) {
                    $specifications[] = [
                        'name' => sanitize($name),
                        'value' => sanitize($_POST['spec_values'][$key])
                    ];
                }
            }
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'price' => (float)($_POST['price'] ?? 0),
            'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'category_id' => $_POST['category_id'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'stock' => (int)($_POST['stock'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured']),
            'thumbnail' => $thumbnail,
            'images' => $images,
            'specifications' => $specifications
        ];
        
        $result = $this->productModel->create($data);
        
        echo json_encode($result);
    }
    
    /**
     * Sửa sản phẩm
     */
    public function editProduct($productId) {
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            flash('error', 'Sản phẩm không tồn tại');
            redirect('/admin/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $this->categoryModel->getAll(['status' => 'active']);
            include __DIR__ . '/../views/admin/product-form.php';
            return;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'price' => (float)($_POST['price'] ?? 0),
            'sale_price' => !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null,
            'category_id' => $_POST['category_id'] ?? '',
            'brand' => $_POST['brand'] ?? '',
            'stock' => (int)($_POST['stock'] ?? 0),
            'status' => $_POST['status'] ?? 'active',
            'featured' => isset($_POST['featured'])
        ];
        
        if (!empty($_FILES['thumbnail']['name'])) {
            $uploadResult = uploadImage($_FILES['thumbnail'], 'products');
            if ($uploadResult['success']) {
                $data['thumbnail'] = $uploadResult['path'];
            }
        }
        
        if (!empty($_FILES['images']['name'][0])) {
            $images = [];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $tmpName,
                    'size' => $_FILES['images']['size'][$key]
                ];
                $uploadResult = uploadImage($file, 'products');
                if ($uploadResult['success']) {
                    $images[] = $uploadResult['path'];
                }
            }
            $data['images'] = $images;
        }
        
        // Xử lý specifications
        $specifications = [];
        if (!empty($_POST['spec_names'])) {
            foreach ($_POST['spec_names'] as $key => $name) {
                if (!empty($name) && !empty($_POST['spec_values'][$key])) {
                    $specifications[] = [
                        'name' => sanitize($name),
                        'value' => sanitize($_POST['spec_values'][$key])
                    ];
                }
            }
        }
        $data['specifications'] = $specifications;
        
        $result = $this->productModel->update($productId, $data);
        
        echo json_encode($result);
    }
    
    /**
     * Xóa sản phẩm
     */
    public function deleteProduct() {
        $productId = $_POST['product_id'] ?? '';
        $result = $this->productModel->delete($productId);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ DANH MỤC ========== */
    
    /**
     * Danh sách danh mục
     */
    public function categories() {
        $categories = $this->categoryModel->getAll();
        include __DIR__ . '/../views/admin/categories.php';
    }
    
    /**
     * Thêm danh mục
     */
    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $parentCategories = $this->categoryModel->getParentCategories();
            include __DIR__ . '/../views/admin/category-form.php';
            return;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
            'icon' => $_POST['icon'] ?? 'fas fa-folder',
            'order' => (int)($_POST['order'] ?? 0),
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = uploadImage($_FILES['image'], 'categories');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }
        
        $result = $this->categoryModel->create($data);
        echo json_encode($result);
    }
    
    /**
     * Sửa danh mục
     */
    public function editCategory($categoryId) {
        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            flash('error', 'Danh mục không tồn tại');
            redirect('/admin/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $parentCategories = $this->categoryModel->getParentCategories();
            include __DIR__ . '/../views/admin/category-form.php';
            return;
        }
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'parent_id' => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
            'icon' => $_POST['icon'] ?? 'fas fa-folder',
            'order' => (int)($_POST['order'] ?? 0),
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (!empty($_FILES['image']['name'])) {
            $uploadResult = uploadImage($_FILES['image'], 'categories');
            if ($uploadResult['success']) {
                $data['image'] = $uploadResult['path'];
            }
        }
        
        $result = $this->categoryModel->update($categoryId, $data);
        echo json_encode($result);
    }
    
    /**
     * Xóa danh mục
     */
    public function deleteCategory() {
        $categoryId = $_POST['category_id'] ?? '';
        $result = $this->categoryModel->delete($categoryId);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ ĐƠN HÀNG ========== */
    
    /**
     * Danh sách đơn hàng
     */
    public function orders() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        // Lấy thống kê theo trạng thái
        $orderStats = $this->orderModel->getStatsByStatus();
        $totalOrders = $this->orderModel->getTotalCount();
        
        $filter = [];
        if ($status) {
            $filter['status'] = $status;
        }
        if ($startDate && $endDate) {
            $filter['created_at'] = [
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($endDate . ' 23:59:59') * 1000)
            ];
        }
        
        if ($search) {
            $result = $this->orderModel->search($search, $page);
        } else {
            $result = $this->orderModel->getAll($filter, $page);
        }
        
        include __DIR__ . '/../views/admin/orders.php';
    }
    
    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($orderId) {
        $order = $this->orderModel->getById($orderId);
        
        if (!$order) {
            flash('error', 'Đơn hàng không tồn tại');
            redirect('/admin/orders');
        }
        
        $customer = $this->userModel->getById($order['user_id']);
        
        include __DIR__ . '/../views/admin/order-detail.php';
    }
    
    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus() {
        $orderId = $_POST['order_id'] ?? '';
        $status = $_POST['status'] ?? '';
        $note = $_POST['note'] ?? '';
        
        $result = $this->orderModel->updateStatus($orderId, $status, $note, $_SESSION['user_id']);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ ĐÁNH GIÁ ========== */
    
    /**
     * Danh sách đánh giá
     */
    public function reviews() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? null;
        
        $filter = [];
        if ($status) {
            $filter['status'] = $status;
        }
        
        $result = $this->reviewModel->getAll($filter, $page);
        
        include __DIR__ . '/../views/admin/reviews.php';
    }
    
    /**
     * Duyệt đánh giá
     */
    public function approveReview() {
        $reviewId = $_POST['review_id'] ?? '';
        $result = $this->reviewModel->approve($reviewId);
        echo json_encode($result);
    }
    
    /**
     * Từ chối đánh giá
     */
    public function rejectReview() {
        $reviewId = $_POST['review_id'] ?? '';
        $reason = $_POST['reason'] ?? '';
        $result = $this->reviewModel->reject($reviewId, $reason);
        echo json_encode($result);
    }
    
    /**
     * Trả lời đánh giá
     */
    public function replyReview() {
        $reviewId = $_POST['review_id'] ?? '';
        $content = $_POST['content'] ?? '';
        $result = $this->reviewModel->reply($reviewId, $content, $_SESSION['user_id']);
        echo json_encode($result);
    }
    
    /**
     * Xóa đánh giá
     */
    public function deleteReview() {
        $reviewId = $_POST['review_id'] ?? '';
        $result = $this->reviewModel->delete($reviewId);
        echo json_encode($result);
    }
    
    /* ========== QUẢN LÝ CHAT ========== */
    
    /**
     * Danh sách cuộc hội thoại
     */
    public function chats() {
        $status = $_GET['status'] ?? null;
        
        $filter = [];
        if ($status) {
            $filter['status'] = $status;
        }
        
        $result = $this->chatModel->getConversations($filter);
        
        include __DIR__ . '/../views/admin/chats.php';
    }
    
    /**
     * Chi tiết cuộc hội thoại
     */
    public function chatDetail($conversationId) {
        $conversation = $this->chatModel->getConversationById($conversationId);
        
        if (!$conversation) {
            flash('error', 'Cuộc hội thoại không tồn tại');
            redirect('/admin/chats');
        }
        
        $messages = $this->chatModel->getMessages($conversationId);
        $this->chatModel->markAsRead($conversationId, 'admin');
        
        include __DIR__ . '/../views/admin/chat-detail.php';
    }
    
    /**
     * Nhận hỗ trợ cuộc hội thoại
     */
    public function assignChat() {
        $conversationId = $_POST['conversation_id'] ?? '';
        $result = $this->chatModel->assignEmployee(
            $conversationId,
            $_SESSION['user_id'],
            $_SESSION['fullname']
        );
        echo json_encode($result);
    }
    
    /**
     * Gửi tin nhắn
     */
    public function sendChatMessage() {
        $conversationId = $_POST['conversation_id'] ?? '';
        $content = $_POST['content'] ?? '';
        
        if (empty($content)) {
            echo json_encode(['success' => false, 'message' => 'Nội dung không được để trống']);
            return;
        }
        
        $result = $this->chatModel->sendMessage(
            $conversationId,
            $_SESSION['user_id'],
            $_SESSION['fullname'],
            'admin',
            $content
        );
        
        echo json_encode($result);
    }
    
    /**
     * Đóng cuộc hội thoại
     */
    public function closeChat() {
        $conversationId = $_POST['conversation_id'] ?? '';
        $result = $this->chatModel->closeConversation($conversationId, $_SESSION['user_id']);
        echo json_encode($result);
    }
}
?>
