<?php
/**
 * Employee Controller
 * Xử lý các chức năng của nhân viên
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Chat.php';

class EmployeeController {
    private $userModel;
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $reviewModel;
    private $chatModel;
    
    public function __construct() {
        // Kiểm tra quyền truy cập
        if (!isEmployee()) {
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
     * Dashboard nhân viên
     */
    public function dashboard() {
        $orderStats = $this->orderModel->getStatistics();
        $pendingOrders = $this->orderModel->getAll(['status' => ORDER_PENDING], 1, 5);
        $recentOrders = $this->orderModel->getRecent(10);
        $pendingReviews = $this->reviewModel->getPending(1, 5);
        $pendingChats = $this->chatModel->countPending();
        
        include __DIR__ . '/../views/employee/dashboard.php';
    }
    
    /**
     * Thông tin nhân viên
     */
    public function profile() {
        $user = $this->userModel->getById($_SESSION['user_id']);
        include __DIR__ . '/../views/employee/profile.php';
    }
    
    /**
     * Cập nhật thông tin nhân viên
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
    
    /* ========== QUẢN LÝ ĐƠN HÀNG ========== */
    
    /**
     * Danh sách đơn hàng
     */
    public function orders() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $filter = [];
        if ($status) {
            $filter['status'] = $status;
        }
        
        if ($search) {
            $result = $this->orderModel->search($search, $page);
        } else {
            $result = $this->orderModel->getAll($filter, $page);
        }
        
        include __DIR__ . '/../views/employee/orders.php';
    }
    
    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($orderId) {
        $order = $this->orderModel->getById($orderId);
        
        if (!$order) {
            flash('error', 'Đơn hàng không tồn tại');
            redirect('/employee/orders');
        }
        
        // Lấy thông tin user đặt hàng
        $customer = $this->userModel->getById($order['user_id']);
        
        include __DIR__ . '/../views/employee/order-detail.php';
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
    
    /* ========== QUẢN LÝ SẢN PHẨM ========== */
    
    /**
     * Danh sách sản phẩm
     */
    public function products() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? '';
        
        $filter = [];
        if ($category) {
            $filter['category_id'] = $category;
        }
        
        if ($search) {
            $result = $this->productModel->search($search, $page, ADMIN_ITEMS_PER_PAGE);
        } else {
            $result = $this->productModel->getAll($filter, ['created_at' => -1], $page, ADMIN_ITEMS_PER_PAGE);
        }
        
        $categories = $this->categoryModel->getAll();
        
        include __DIR__ . '/../views/employee/products.php';
    }
    
    /**
     * Thêm sản phẩm mới
     */
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $this->categoryModel->getAll(['status' => 'active']);
            include __DIR__ . '/../views/employee/product-form.php';
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
            redirect('/employee/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = $this->categoryModel->getAll(['status' => 'active']);
            include __DIR__ . '/../views/employee/product-form.php';
            return;
        }
        
        // Upload thumbnail mới nếu có
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
        
        // Upload images mới nếu có
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
        
        include __DIR__ . '/../views/employee/reviews.php';
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
        
        include __DIR__ . '/../views/employee/chats.php';
    }
    
    /**
     * Chi tiết cuộc hội thoại
     */
    public function chatDetail($conversationId) {
        $conversation = $this->chatModel->getConversationById($conversationId);
        
        if (!$conversation) {
            flash('error', 'Cuộc hội thoại không tồn tại');
            redirect('/employee/chats');
        }
        
        $messages = $this->chatModel->getMessages($conversationId);
        $this->chatModel->markAsRead($conversationId, 'employee');
        
        include __DIR__ . '/../views/employee/chat-detail.php';
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
     * Gửi tin nhắn chat
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
            'employee',
            $content
        );
        
        echo json_encode($result);
    }
    
    /**
     * Lấy tin nhắn mới
     */
    public function getChatMessages() {
        $conversationId = $_GET['conversation_id'] ?? '';
        $messages = $this->chatModel->getMessages($conversationId);
        echo json_encode(['success' => true, 'messages' => $messages]);
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
