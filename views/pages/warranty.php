<?php
/**
 * Warranty Policy Page - TechShop
 */

$pageTitle = 'Chính sách bảo hành';
$currentPage = 'warranty';

ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Chính sách bảo hành</li>
                </ol>
            </nav>
            
            <h1 class="fw-bold mb-4">Chính sách bảo hành</h1>
            
            <div class="alert alert-primary d-flex align-items-center mb-4">
                <i class="fas fa-shield-alt fs-4 me-3"></i>
                <div>
                    <strong>TechShop cam kết:</strong> Tất cả sản phẩm đều được bảo hành chính hãng, 
                    đúng quy định của nhà sản xuất.
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-clock me-2"></i>1. Thời gian bảo hành
                    </h4>
                    <p class="text-muted">
                        Thời gian bảo hành được tính từ ngày mua hàng và áp dụng theo quy định của từng hãng:
                    </p>
                    <ul class="text-muted">
                        <li><strong>Laptop, PC:</strong> 12 - 36 tháng tùy hãng</li>
                        <li><strong>Màn hình:</strong> 24 - 36 tháng</li>
                        <li><strong>Linh kiện (CPU, RAM, SSD, VGA...):</strong> 36 tháng</li>
                        <li><strong>Chuột, bàn phím:</strong> 12 - 24 tháng</li>
                        <li><strong>Tai nghe, loa:</strong> 12 tháng</li>
                        <li><strong>Phụ kiện:</strong> 6 - 12 tháng</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-check-circle me-2"></i>2. Điều kiện bảo hành
                    </h4>
                    <p class="text-muted">Sản phẩm được bảo hành khi đáp ứng các điều kiện sau:</p>
                    <ul class="text-muted">
                        <li>Sản phẩm còn trong thời hạn bảo hành</li>
                        <li>Tem bảo hành, serial number còn nguyên vẹn</li>
                        <li>Sản phẩm bị lỗi do nhà sản xuất</li>
                        <li>Có phiếu bảo hành hoặc hóa đơn mua hàng</li>
                        <li>Sản phẩm không bị tác động vật lý, va đập, vào nước</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-danger mb-3">
                        <i class="fas fa-times-circle me-2"></i>3. Các trường hợp không được bảo hành
                    </h4>
                    <ul class="text-muted">
                        <li>Sản phẩm hết thời hạn bảo hành</li>
                        <li>Tem bảo hành bị rách, mờ, không còn nguyên vẹn</li>
                        <li>Serial number bị cạo sửa, tẩy xóa</li>
                        <li>Sản phẩm bị hư hỏng do:
                            <ul>
                                <li>Tác động vật lý: rơi, va đập, trầy xước</li>
                                <li>Vào nước, hóa chất, chất lỏng</li>
                                <li>Cháy nổ, thiên tai, sét đánh</li>
                                <li>Sử dụng sai cách, quá tải</li>
                                <li>Nguồn điện không ổn định</li>
                                <li>Tự ý sửa chữa, can thiệp phần cứng/phần mềm</li>
                            </ul>
                        </li>
                        <li>Không có hóa đơn mua hàng hoặc phiếu bảo hành</li>
                        <li>Sản phẩm bị virus, lỗi phần mềm do người dùng</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-tools me-2"></i>4. Quy trình bảo hành
                    </h4>
                    <div class="row g-4">
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">1</div>
                            <h6 class="fw-bold">Tiếp nhận</h6>
                            <p class="small text-muted">Mang sản phẩm đến showroom hoặc gửi qua bưu điện</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">2</div>
                            <h6 class="fw-bold">Kiểm tra</h6>
                            <p class="small text-muted">Kỹ thuật kiểm tra và xác định lỗi</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">3</div>
                            <h6 class="fw-bold">Xử lý</h6>
                            <p class="small text-muted">Sửa chữa hoặc đổi mới theo chính sách</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">4</div>
                            <h6 class="fw-bold">Hoàn trả</h6>
                            <p class="small text-muted">Trả sản phẩm cho khách hàng</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-calendar-alt me-2"></i>5. Thời gian xử lý bảo hành
                    </h4>
                    <ul class="text-muted">
                        <li><strong>Kiểm tra, báo giá (nếu có):</strong> 1-3 ngày làm việc</li>
                        <li><strong>Sửa chữa tại TechShop:</strong> 3-7 ngày làm việc</li>
                        <li><strong>Sửa chữa tại hãng:</strong> 7-21 ngày làm việc</li>
                        <li><strong>Đổi mới:</strong> 1-7 ngày làm việc (tùy thuộc tình trạng hàng)</li>
                    </ul>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thời gian có thể thay đổi tùy tình trạng sản phẩm và quy định của hãng.
                    </p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>6. Địa điểm bảo hành
                    </h4>
                    <p class="text-muted">
                        Quý khách có thể mang sản phẩm đến bất kỳ showroom nào của TechShop trên toàn quốc 
                        hoặc gửi qua đường bưu điện về địa chỉ:
                    </p>
                    <div class="bg-light p-3 rounded">
                        <strong>Trung tâm Bảo hành TechShop</strong><br>
                        123 Nguyễn Văn Linh, Quận 7, TP. Hồ Chí Minh<br>
                        Hotline: <a href="tel:1900xxxx">1900 xxxx</a>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3">Cần hỗ trợ bảo hành?</h4>
                    <p class="mb-3">Liên hệ với chúng tôi để được tư vấn và hỗ trợ nhanh nhất</p>
                    <a href="/contact" class="btn btn-light btn-lg">
                        <i class="fas fa-phone-alt me-2"></i>Liên hệ ngay
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


