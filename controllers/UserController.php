<?php
/**
 * User Controller
 * Xử lý các chức năng của người dùng
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Chat.php';

class UserController {
    private $userModel;
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $cartModel;
    private $reviewModel;
    private $chatModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        $this->reviewModel = new Review();
        $this->chatModel = new Chat();
    }
    
    /**
     * Trang chủ
     */
    public function home() {
        $featuredProducts = $this->productModel->getFeatured(8);
        $newProducts = $this->productModel->getNew(8);
        $bestSellingProducts = $this->productModel->getBestSelling(8);
        $saleProducts = $this->productModel->getOnSale(8);
        $categories = $this->categoryModel->getParentCategories();
        
        include __DIR__ . '/../views/user/home.php';
    }
    
    /**
     * Trang danh sách sản phẩm
     */
    public function products() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $categorySlug = $_GET['category'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';
        $minPrice = $_GET['min_price'] ?? null;
        $maxPrice = $_GET['max_price'] ?? null;
        
        $filters = ['status' => 'active'];
        $category = null;
        
        // Nếu có category slug, chuyển thành ID
        if ($categorySlug) {
            $category = $this->categoryModel->findBySlug($categorySlug);
            if ($category) {
                $filters['category_id'] = $category['id'];
            }
        }
        
        // Filter theo giá
        if ($minPrice !== null) {
            $filters['min_price'] = (float)$minPrice;
        }
        if ($maxPrice !== null) {
            $filters['max_price'] = (float)$maxPrice;
        }
        
        // Sort
        if ($sort) {
            $filters['sort'] = $sort;
        }
        
        $result = $this->productModel->getAll($page, 12, $filters);
        $products = $result['products'] ?? [];
        $total = $result['total'] ?? 0;
        $totalPages = $result['totalPages'] ?? 1;
        
        $categories = $this->categoryModel->getAll('active');
        $brands = $this->productModel->getBrandsWithCount();
        
        include __DIR__ . '/../views/user/products.php';
    }
    
    /**
     * Trang chi tiết sản phẩm
     */
    public function productDetail($slug) {
        $product = $this->productModel->getBySlug($slug);
        
        if (!$product) {
            http_response_code(404);
            include __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $relatedProducts = $this->productModel->getRelated(
            (string)$product['_id'],
            $product['category_id'],
            4
        );
        
        $reviews = $this->reviewModel->getByProduct((string)$product['_id']);
        $category = $this->categoryModel->getById($product['category_id']);
        
        // Kiểm tra user có thể đánh giá không
        $canReview = false;
        if (isLoggedIn()) {
            $canReview = $this->reviewModel->canReview($_SESSION['user_id'], (string)$product['_id']);
        }
        
        include __DIR__ . '/../views/user/product-detail.php';
    }
    
    /**
     * Tìm kiếm sản phẩm
     */
    public function search() {
        $keyword = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $result = $this->productModel->search($keyword, $page);
        $categories = $this->categoryModel->getCategoryTree();
        
        include __DIR__ . '/../views/user/search.php';
    }
    
    /**
     * Trang giỏ hàng
     */
    public function cart() {
        $cart = [];
        $total = 0;
        
        if (isLoggedIn()) {
            $cartData = $this->cartModel->getCart($_SESSION['user_id']);
            $cart = $cartData['items'] ?? [];
            $total = $cartData['total'] ?? 0;
        } else {
            $cart = $_SESSION['cart'] ?? [];
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        
        include __DIR__ . '/../views/user/cart.php';
    }
    
    /**
     * Thêm vào giỏ hàng
     */
    public function addToCart() {
        $productId = $_POST['product_id'] ?? '';
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if (empty($productId)) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
            return;
        }
        
        if (isLoggedIn()) {
            $result = $this->cartModel->addItem($_SESSION['user_id'], $productId, $quantity);
        } else {
            // Lưu vào session
            $product = $this->productModel->getById($productId);
            
            if (!$product) {
                echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
                return;
            }
            
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] === $productId) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $_SESSION['cart'][] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['sale_price'] ?? $product['price'],
                    'image' => $product['thumbnail'],
                    'quantity' => $quantity
                ];
            }
            
            $result = ['success' => true, 'message' => 'Đã thêm vào giỏ hàng'];
        }
        
        echo json_encode($result);
    }
    
    /**
     * Cập nhật giỏ hàng
     */
    public function updateCart() {
        $productId = $_POST['product_id'] ?? '';
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        if (isLoggedIn()) {
            $result = $this->cartModel->updateQuantity($_SESSION['user_id'], $productId, $quantity);
        } else {
            if ($quantity <= 0) {
                $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
                    return $item['product_id'] !== $productId;
                });
            } else {
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['product_id'] === $productId) {
                        $item['quantity'] = $quantity;
                        break;
                    }
                }
            }
            $result = ['success' => true, 'message' => 'Đã cập nhật giỏ hàng'];
        }
        
        echo json_encode($result);
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart() {
        $productId = $_POST['product_id'] ?? '';
        
        if (isLoggedIn()) {
            $result = $this->cartModel->removeItem($_SESSION['user_id'], $productId);
        } else {
            $_SESSION['cart'] = array_filter($_SESSION['cart'] ?? [], function($item) use ($productId) {
                return $item['product_id'] !== $productId;
            });
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $result = ['success' => true, 'message' => 'Đã xóa sản phẩm'];
        }
        
        echo json_encode($result);
    }
    
    /**
     * Trang thanh toán
     */
    public function checkout() {
        if (!isLoggedIn()) {
            flash('error', 'Vui lòng đăng nhập để thanh toán');
            redirect('/login');
        }
        
        $cartData = $this->cartModel->getCart($_SESSION['user_id']);
        $cart = $cartData['items'] ?? [];
        
        if (empty($cart)) {
            flash('error', 'Giỏ hàng trống');
            redirect('/cart');
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        $total = $cartData['total'] ?? 0;
        $shippingFee = 30000; // Phí ship mặc định
        
        include __DIR__ . '/../views/user/checkout.php';
    }
    
    /**
     * Đặt hàng
     */
    public function placeOrder() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $cartData = $this->cartModel->getCart($_SESSION['user_id']);
        $cart = $cartData['items'] ?? [];
        
        if (empty($cart)) {
            echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
            return;
        }
        
        $orderData = [
            'user_id' => $_SESSION['user_id'],
            'items' => $cart,
            'subtotal' => $cartData['total'],
            'shipping_fee' => (float)($_POST['shipping_fee'] ?? 30000),
            'discount' => (float)($_POST['discount'] ?? 0),
            'total' => $cartData['total'] + (float)($_POST['shipping_fee'] ?? 30000) - (float)($_POST['discount'] ?? 0),
            'fullname' => $_POST['fullname'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'district' => $_POST['district'] ?? '',
            'ward' => $_POST['ward'] ?? '',
            'note' => $_POST['note'] ?? '',
            'payment_method' => $_POST['payment_method'] ?? PAYMENT_COD,
            'coupon_code' => $_POST['coupon_code'] ?? null
        ];
        
        $result = $this->orderModel->create($orderData);
        
        if ($result['success']) {
            // Xóa giỏ hàng
            $this->cartModel->clearCart($_SESSION['user_id']);
        }
        
        echo json_encode($result);
    }
    
    /**
     * Trang đơn hàng của tôi
     */
    public function orders() {
        if (!isLoggedIn()) {
            redirect('/login');
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? null;
        
        $result = $this->orderModel->getByUser($_SESSION['user_id'], $status, $page);
        
        include __DIR__ . '/../views/user/orders.php';
    }
    
    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail($orderId) {
        if (!isLoggedIn()) {
            redirect('/login');
        }
        
        $order = $this->orderModel->getById($orderId);
        
        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            flash('error', 'Đơn hàng không tồn tại');
            redirect('/user/orders');
        }
        
        include __DIR__ . '/../views/user/order-detail.php';
    }
    
    /**
     * Hủy đơn hàng
     */
    public function cancelOrder() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $orderId = $_POST['order_id'] ?? '';
        $reason = $_POST['reason'] ?? '';
        
        $result = $this->orderModel->cancel($orderId, $_SESSION['user_id'], $reason);
        
        echo json_encode($result);
    }
    
    /**
     * Trang thông tin cá nhân
     */
    public function profile() {
        if (!isLoggedIn()) {
            redirect('/login');
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        
        include __DIR__ . '/../views/user/profile.php';
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? ''
        ];
        
        // Upload avatar nếu có
        if (!empty($_FILES['avatar']['name'])) {
            $uploadResult = uploadImage($_FILES['avatar'], 'avatars');
            if ($uploadResult['success']) {
                $data['avatar'] = $uploadResult['path'];
            }
        }
        
        $result = $this->userModel->updateProfile($_SESSION['user_id'], $data);
        
        echo json_encode($result);
    }
    
    /**
     * Đổi mật khẩu
     */
    public function changePassword() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp']);
            return;
        }
        
        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
            return;
        }
        
        $result = $this->userModel->changePassword($_SESSION['user_id'], $oldPassword, $newPassword);
        
        echo json_encode($result);
    }
    
    /**
     * Gửi đánh giá sản phẩm
     */
    public function submitReview() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $data = [
            'user_id' => $_SESSION['user_id'],
            'user_name' => $_SESSION['fullname'],
            'user_avatar' => $_SESSION['avatar'] ?? 'default-avatar.png',
            'product_id' => $_POST['product_id'] ?? '',
            'rating' => (int)($_POST['rating'] ?? 5),
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? ''
        ];
        
        // Upload ảnh review nếu có
        if (!empty($_FILES['images']['name'][0])) {
            $images = [];
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $file = [
                    'name' => $_FILES['images']['name'][$key],
                    'tmp_name' => $tmpName,
                    'size' => $_FILES['images']['size'][$key]
                ];
                $uploadResult = uploadImage($file, 'reviews');
                if ($uploadResult['success']) {
                    $images[] = $uploadResult['path'];
                }
            }
            $data['images'] = $images;
        }
        
        $result = $this->reviewModel->create($data);
        
        echo json_encode($result);
    }
    
    /**
     * Bắt đầu chat
     */
    public function startChat() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $conversation = $this->chatModel->getOrCreateConversation(
            $_SESSION['user_id'],
            $_SESSION['fullname']
        );
        
        if ($conversation) {
            $messages = $this->chatModel->getMessages((string)$conversation['_id']);
            echo json_encode([
                'success' => true,
                'conversation' => $conversation,
                'messages' => $messages
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể tạo cuộc hội thoại']);
        }
    }
    
    /**
     * Gửi tin nhắn chat
     */
    public function sendChatMessage() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
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
            'user',
            $content
        );
        
        echo json_encode($result);
    }
    
    /**
     * Lấy tin nhắn chat mới
     */
    public function getChatMessages() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $conversationId = $_GET['conversation_id'] ?? '';
        
        $messages = $this->chatModel->getMessages($conversationId);
        $this->chatModel->markAsRead($conversationId, 'user');
        
        echo json_encode(['success' => true, 'messages' => $messages]);
    }
}
?>
