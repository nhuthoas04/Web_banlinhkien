<?php
/**
 * Shopping Guide Page - TechShop
 */

$pageTitle = 'Hướng dẫn mua hàng';
$currentPage = 'guide';

ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Hướng dẫn mua hàng</li>
                </ol>
            </nav>
            
            <h1 class="fw-bold mb-4">Hướng dẫn mua hàng</h1>
            
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="fas fa-info-circle fs-4 me-3"></i>
                <div>
                    Mua hàng tại TechShop rất đơn giản! Chỉ cần vài bước là bạn đã có thể sở hữu 
                    sản phẩm công nghệ yêu thích.
                </div>
            </div>
            
            <!-- Step 1 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">1</div>
                        <h4 class="fw-bold mb-0 text-primary">Tìm kiếm sản phẩm</h4>
                    </div>
                    <p class="text-muted mb-3">Có nhiều cách để tìm sản phẩm bạn muốn:</p>
                    <ul class="text-muted">
                        <li><strong>Thanh tìm kiếm:</strong> Nhập tên sản phẩm, hãng hoặc mã sản phẩm</li>
                        <li><strong>Danh mục:</strong> Duyệt theo danh mục sản phẩm (Laptop, PC, Linh kiện...)</li>
                        <li><strong>Bộ lọc:</strong> Lọc theo giá, thương hiệu, cấu hình...</li>
                        <li><strong>Sản phẩm nổi bật:</strong> Xem các sản phẩm được đề xuất trên trang chủ</li>
                    </ul>
                    <div class="bg-light p-3 rounded">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <small><strong>Mẹo:</strong> Sử dụng tính năng "So sánh sản phẩm" để chọn được sản phẩm phù hợp nhất!</small>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">2</div>
                        <h4 class="fw-bold mb-0 text-primary">Xem chi tiết sản phẩm</h4>
                    </div>
                    <p class="text-muted mb-3">Nhấp vào sản phẩm để xem thông tin chi tiết:</p>
                    <ul class="text-muted">
                        <li>Hình ảnh sản phẩm chi tiết từ nhiều góc</li>
                        <li>Thông số kỹ thuật đầy đủ</li>
                        <li>Giá bán và khuyến mại hiện có</li>
                        <li>Tình trạng còn hàng</li>
                        <li>Đánh giá từ người mua trước</li>
                    </ul>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">3</div>
                        <h4 class="fw-bold mb-0 text-primary">Thêm vào giỏ hàng</h4>
                    </div>
                    <p class="text-muted mb-3">Khi đã chọn được sản phẩm ưng ý:</p>
                    <ul class="text-muted">
                        <li>Chọn số lượng muốn mua</li>
                        <li>Nhấn nút <strong>"Thêm vào giỏ hàng"</strong></li>
                        <li>Sản phẩm sẽ được lưu trong giỏ hàng của bạn</li>
                        <li>Tiếp tục mua sắm hoặc tiến hành thanh toán</li>
                    </ul>
                    <div class="bg-light p-3 rounded">
                        <i class="fas fa-heart text-danger me-2"></i>
                        <small><strong>Yêu thích:</strong> Nhấn "Thêm vào yêu thích" để lưu sản phẩm và mua sau!</small>
                    </div>
                </div>
            </div>
            
            <!-- Step 4 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">4</div>
                        <h4 class="fw-bold mb-0 text-primary">Kiểm tra giỏ hàng</h4>
                    </div>
                    <p class="text-muted mb-3">Tại trang giỏ hàng, bạn có thể:</p>
                    <ul class="text-muted">
                        <li>Xem danh sách sản phẩm đã chọn</li>
                        <li>Thay đổi số lượng hoặc xóa sản phẩm</li>
                        <li>Nhập mã giảm giá (nếu có)</li>
                        <li>Xem tổng tiền cần thanh toán</li>
                    </ul>
                </div>
            </div>
            
            <!-- Step 5 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">5</div>
                        <h4 class="fw-bold mb-0 text-primary">Đăng nhập / Đăng ký</h4>
                    </div>
                    <p class="text-muted mb-3">Để tiến hành thanh toán:</p>
                    <ul class="text-muted mb-3">
                        <li><strong>Đã có tài khoản:</strong> Đăng nhập với email và mật khẩu</li>
                        <li><strong>Chưa có tài khoản:</strong> Đăng ký nhanh chỉ với vài thông tin cơ bản</li>
                    </ul>
                    <p class="text-muted">
                        <i class="fas fa-gift text-success me-2"></i>
                        Đăng ký thành viên để tích điểm, nhận ưu đãi độc quyền!
                    </p>
                </div>
            </div>
            
            <!-- Step 6 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">6</div>
                        <h4 class="fw-bold mb-0 text-primary">Điền thông tin giao hàng</h4>
                    </div>
                    <p class="text-muted mb-3">Điền đầy đủ thông tin để nhận hàng:</p>
                    <ul class="text-muted">
                        <li>Họ tên người nhận</li>
                        <li>Số điện thoại liên hệ</li>
                        <li>Địa chỉ giao hàng chi tiết</li>
                        <li>Ghi chú cho đơn hàng (nếu có)</li>
                    </ul>
                    <div class="bg-light p-3 rounded">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        <small><strong>Lưu địa chỉ:</strong> Lưu địa chỉ thường xuyên để đặt hàng nhanh hơn!</small>
                    </div>
                </div>
            </div>
            
            <!-- Step 7 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">7</div>
                        <h4 class="fw-bold mb-0 text-primary">Chọn phương thức thanh toán</h4>
                    </div>
                    <p class="text-muted mb-3">TechShop hỗ trợ nhiều hình thức thanh toán:</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold"><i class="fas fa-money-bill-wave text-success me-2"></i>COD</h6>
                                <small class="text-muted">Thanh toán khi nhận hàng</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold"><i class="fas fa-university text-primary me-2"></i>Chuyển khoản</h6>
                                <small class="text-muted">Chuyển khoản ngân hàng</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold"><i class="fas fa-wallet text-info me-2"></i>Ví điện tử</h6>
                                <small class="text-muted">Momo, ZaloPay, VNPay</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold"><i class="fas fa-credit-card text-warning me-2"></i>Thẻ</h6>
                                <small class="text-muted">Visa, Mastercard, JCB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 8 -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="step-circle bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 45px; height: 45px; font-weight: bold; font-size: 1.2rem;">8</div>
                        <h4 class="fw-bold mb-0 text-success">Xác nhận đơn hàng</h4>
                    </div>
                    <p class="text-muted mb-3">Kiểm tra lại thông tin và hoàn tất:</p>
                    <ul class="text-muted">
                        <li>Xem lại sản phẩm, số lượng, giá tiền</li>
                        <li>Kiểm tra địa chỉ giao hàng</li>
                        <li>Xác nhận phương thức thanh toán</li>
                        <li>Nhấn <strong>"Đặt hàng"</strong> để hoàn tất</li>
                    </ul>
                    <div class="bg-success bg-opacity-10 p-3 rounded text-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Thành công!</strong> Bạn sẽ nhận được email/SMS xác nhận đơn hàng.
                    </div>
                </div>
            </div>
            
            <!-- Tracking -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-truck me-2"></i>Theo dõi đơn hàng
                    </h4>
                    <p class="text-muted mb-3">Theo dõi tình trạng đơn hàng dễ dàng:</p>
                    <ul class="text-muted">
                        <li>Đăng nhập và vào <strong>"Đơn hàng của tôi"</strong></li>
                        <li>Xem trạng thái: Chờ xác nhận → Đang xử lý → Đang giao → Hoàn thành</li>
                        <li>Nhận thông báo qua email/SMS khi có cập nhật</li>
                    </ul>
                </div>
            </div>
            
            <!-- FAQ -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-question-circle me-2"></i>Câu hỏi thường gặp
                    </h4>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Tôi có thể hủy đơn hàng không?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Có, bạn có thể hủy đơn hàng khi đơn hàng chưa được giao cho đơn vị vận chuyển. 
                                    Vào "Đơn hàng của tôi", chọn đơn hàng và nhấn "Hủy đơn hàng".
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Phí vận chuyển là bao nhiêu?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Miễn phí vận chuyển cho đơn hàng từ 500.000đ trong nội thành. 
                                    Đơn hàng dưới 500.000đ hoặc tỉnh khác có phí từ 20.000đ - 50.000đ tùy khu vực.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Thời gian giao hàng là bao lâu?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Nội thành HCM, Hà Nội: 1-2 ngày. Các tỉnh lân cận: 2-3 ngày. 
                                    Các tỉnh xa: 3-5 ngày. Đơn hàng đặt trước 14h sẽ được giao trong ngày (nội thành).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="card border-0 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3">Bắt đầu mua sắm ngay!</h4>
                    <p class="mb-3">Khám phá hàng ngàn sản phẩm công nghệ chính hãng với giá tốt nhất</p>
                    <a href="/products" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Xem sản phẩm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>


