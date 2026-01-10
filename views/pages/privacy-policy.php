<?php 
$pageTitle = 'Chính sách bảo mật - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active">Chính sách bảo mật</li>
            </ol>
        </nav>
        <h1><i class="fas fa-shield-alt me-2"></i>Chính sách bảo mật</h1>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="policy-content bg-white p-4 p-md-5 rounded-4 shadow-sm">
                    
                    <div class="policy-intro mb-5">
                        <p class="lead">TechShop cam kết bảo vệ quyền riêng tư và thông tin cá nhân của khách hàng. Chính sách bảo mật này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin của bạn.</p>
                        <p class="text-muted"><i class="fas fa-calendar-alt me-1"></i> Cập nhật lần cuối: <?= date('d/m/Y') ?></p>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-database me-2"></i>1. Thông tin chúng tôi thu thập
                        </h3>
                        <p>Khi bạn sử dụng dịch vụ của TechShop, chúng tôi có thể thu thập các thông tin sau:</p>
                        <ul class="policy-list">
                            <li><strong>Thông tin cá nhân:</strong> Họ tên, email, số điện thoại, địa chỉ giao hàng</li>
                            <li><strong>Thông tin tài khoản:</strong> Email đăng nhập, mật khẩu (được mã hóa)</li>
                            <li><strong>Thông tin đơn hàng:</strong> Lịch sử mua hàng, sản phẩm yêu thích</li>
                            <li><strong>Thông tin thanh toán:</strong> Phương thức thanh toán (không lưu thông tin thẻ)</li>
                            <li><strong>Thông tin kỹ thuật:</strong> Địa chỉ IP, loại trình duyệt, thiết bị sử dụng</li>
                        </ul>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-cogs me-2"></i>2. Cách chúng tôi sử dụng thông tin
                        </h3>
                        <p>Thông tin của bạn được sử dụng cho các mục đích sau:</p>
                        <ul class="policy-list">
                            <li>Xử lý đơn hàng và giao hàng</li>
                            <li>Liên hệ hỗ trợ khách hàng</li>
                            <li>Gửi thông báo về đơn hàng, khuyến mãi (nếu bạn đồng ý)</li>
                            <li>Cải thiện trải nghiệm người dùng</li>
                            <li>Phân tích và thống kê</li>
                            <li>Bảo mật tài khoản</li>
                        </ul>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-lock me-2"></i>3. Bảo vệ thông tin
                        </h3>
                        <p>Chúng tôi áp dụng các biện pháp bảo mật sau:</p>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="security-item p-3 bg-light rounded">
                                    <i class="fas fa-key text-primary me-2"></i>
                                    <strong>Mã hóa mật khẩu</strong>
                                    <p class="mb-0 small text-muted">Sử dụng bcrypt hashing</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="security-item p-3 bg-light rounded">
                                    <i class="fas fa-shield-alt text-primary me-2"></i>
                                    <strong>Bảo vệ SQL Injection</strong>
                                    <p class="mb-0 small text-muted">PDO Prepared Statements</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="security-item p-3 bg-light rounded">
                                    <i class="fas fa-user-shield text-primary me-2"></i>
                                    <strong>Phân quyền truy cập</strong>
                                    <p class="mb-0 small text-muted">Role-based access control</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="security-item p-3 bg-light rounded">
                                    <i class="fas fa-history text-primary me-2"></i>
                                    <strong>Token hết hạn</strong>
                                    <p class="mb-0 small text-muted">Session timeout & token expiry</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-share-alt me-2"></i>4. Chia sẻ thông tin
                        </h3>
                        <p>Chúng tôi <strong>KHÔNG</strong> bán, cho thuê hoặc trao đổi thông tin cá nhân của bạn với bên thứ ba, ngoại trừ:</p>
                        <ul class="policy-list">
                            <li>Đơn vị vận chuyển (để giao hàng)</li>
                            <li>Cổng thanh toán (để xử lý thanh toán)</li>
                            <li>Khi có yêu cầu từ cơ quan pháp luật</li>
                            <li>Khi có sự đồng ý của bạn</li>
                        </ul>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-cookie-bite me-2"></i>5. Cookie
                        </h3>
                        <p>Website sử dụng cookie để:</p>
                        <ul class="policy-list">
                            <li>Ghi nhớ thông tin đăng nhập</li>
                            <li>Lưu sản phẩm trong giỏ hàng</li>
                            <li>Phân tích lưu lượng truy cập</li>
                        </ul>
                        <p>Bạn có thể tắt cookie trong cài đặt trình duyệt, nhưng một số tính năng có thể không hoạt động.</p>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-user-check me-2"></i>6. Quyền của bạn
                        </h3>
                        <p>Bạn có quyền:</p>
                        <ul class="policy-list">
                            <li>Truy cập và xem thông tin cá nhân</li>
                            <li>Chỉnh sửa thông tin không chính xác</li>
                            <li>Yêu cầu xóa tài khoản</li>
                            <li>Từ chối nhận email marketing</li>
                            <li>Khiếu nại về việc xử lý dữ liệu</li>
                        </ul>
                    </div>
                    
                    <div class="policy-section mb-5">
                        <h3 class="text-primary mb-3">
                            <i class="fas fa-sync-alt me-2"></i>7. Cập nhật chính sách
                        </h3>
                        <p>Chính sách bảo mật có thể được cập nhật. Mọi thay đổi sẽ được thông báo trên website. Việc tiếp tục sử dụng dịch vụ đồng nghĩa với việc bạn chấp nhận chính sách mới.</p>
                    </div>
                    
                    <div class="policy-contact bg-primary text-white p-4 rounded-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">Có câu hỏi về chính sách bảo mật?</h4>
                                <p class="mb-0">Liên hệ với chúng tôi để được giải đáp.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="<?= BASE_URL ?>lien-he" class="btn btn-light btn-lg">
                                    <i class="fas fa-envelope me-2"></i>Liên hệ
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: #fff;
    padding: 30px 0;
    margin-bottom: 0;
}

.page-header .breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 10px;
}

.page-header .breadcrumb-item a {
    color: rgba(255,255,255,0.8);
}

.page-header .breadcrumb-item.active {
    color: #fff;
}

.page-header h1 {
    margin: 0;
    font-size: 2rem;
}

.policy-content h3 {
    font-size: 1.3rem;
    font-weight: 600;
}

.policy-list {
    padding-left: 20px;
}

.policy-list li {
    margin-bottom: 10px;
    position: relative;
}

.security-item {
    transition: transform 0.2s;
}

.security-item:hover {
    transform: translateY(-3px);
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
