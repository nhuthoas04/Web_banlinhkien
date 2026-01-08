<?php
/**
 * Return Policy Page - TechShop
 */

$pageTitle = 'Chính sách đổi trả - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Chính sách đổi trả</li>
                </ol>
            </nav>
            
            <h1 class="fw-bold mb-4">Chính sách đổi trả</h1>
            
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-sync-alt fs-4 me-3"></i>
                <div>
                    <strong>TechShop cam kết:</strong> Đổi trả miễn phí trong 7 ngày đầu tiên 
                    nếu sản phẩm có lỗi từ nhà sản xuất.
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-exchange-alt me-2"></i>1. Chính sách đổi hàng
                    </h4>
                    
                    <h6 class="fw-bold">1.1. Đổi hàng trong 7 ngày</h6>
                    <ul class="text-muted mb-4">
                        <li>Áp dụng cho sản phẩm bị lỗi từ nhà sản xuất</li>
                        <li>Sản phẩm chưa kích hoạt bảo hành online (nếu có)</li>
                        <li>Sản phẩm còn nguyên tem, hộp, phụ kiện đi kèm</li>
                        <li>Không có dấu hiệu sử dụng, trầy xước</li>
                    </ul>
                    
                    <h6 class="fw-bold">1.2. Đổi hàng do đổi ý (trong 24h)</h6>
                    <ul class="text-muted">
                        <li>Áp dụng cho các sản phẩm chưa khui seal</li>
                        <li>Khách hàng chịu phí chênh lệch nếu đổi sang sản phẩm khác giá trị</li>
                        <li>Không áp dụng cho sản phẩm sale, flash sale</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-undo-alt me-2"></i>2. Chính sách trả hàng
                    </h4>
                    
                    <h6 class="fw-bold">2.1. Trả hàng trong 7 ngày</h6>
                    <ul class="text-muted mb-4">
                        <li>Sản phẩm bị lỗi từ nhà sản xuất và không có hàng đổi</li>
                        <li>Hoàn tiền 100% giá trị sản phẩm</li>
                        <li>Thời gian hoàn tiền: 3-5 ngày làm việc sau khi nhận hàng</li>
                    </ul>
                    
                    <h6 class="fw-bold">2.2. Phương thức hoàn tiền</h6>
                    <ul class="text-muted">
                        <li>Chuyển khoản ngân hàng</li>
                        <li>Ví điện tử (Momo, ZaloPay, VNPay)</li>
                        <li>Hoàn tiền vào tài khoản TechShop để mua hàng sau</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>3. Điều kiện đổi trả
                    </h4>
                    <p class="text-muted">Sản phẩm được đổi trả khi đáp ứng các điều kiện sau:</p>
                    <ul class="text-muted">
                        <li>Còn trong thời hạn đổi trả</li>
                        <li>Còn đầy đủ hộp, phụ kiện, quà tặng kèm (nếu có)</li>
                        <li>Có hóa đơn mua hàng</li>
                        <li>Sản phẩm chưa bị tác động vật lý, không có dấu hiệu sử dụng nếu đổi do đổi ý</li>
                        <li>Sản phẩm chưa kích hoạt bảo hành điện tử (với các hãng có bảo hành điện tử)</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-danger mb-3">
                        <i class="fas fa-times-circle me-2"></i>4. Các trường hợp không áp dụng đổi trả
                    </h4>
                    <ul class="text-muted">
                        <li>Sản phẩm đã quá thời hạn đổi trả</li>
                        <li>Sản phẩm không có hóa đơn mua hàng</li>
                        <li>Sản phẩm đã kích hoạt bảo hành điện tử</li>
                        <li>Sản phẩm bị hư hỏng do lỗi người dùng</li>
                        <li>Sản phẩm đã qua sử dụng, có dấu hiệu trầy xước</li>
                        <li>Thiếu phụ kiện, quà tặng kèm theo</li>
                        <li>Phần mềm, license key đã kích hoạt</li>
                        <li>Sản phẩm khuyến mại, thanh lý</li>
                        <li>Sản phẩm mua trong các chương trình flash sale với giá đặc biệt</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-clipboard-list me-2"></i>5. Quy trình đổi trả
                    </h4>
                    <div class="row g-4">
                        <div class="col-md-4 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">1</div>
                            <h6 class="fw-bold">Liên hệ</h6>
                            <p class="small text-muted">
                                Gọi hotline 1900 xxxx hoặc đến showroom gần nhất
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">2</div>
                            <h6 class="fw-bold">Kiểm tra</h6>
                            <p class="small text-muted">
                                Nhân viên kiểm tra sản phẩm và xác nhận điều kiện đổi trả
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3" 
                                 style="width: 50px; height: 50px; line-height: 50px; font-weight: bold;">3</div>
                            <h6 class="fw-bold">Xử lý</h6>
                            <p class="small text-muted">
                                Đổi sản phẩm mới hoặc hoàn tiền theo yêu cầu
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-3">
                        <i class="fas fa-shipping-fast me-2"></i>6. Chi phí vận chuyển đổi trả
                    </h4>
                    <ul class="text-muted">
                        <li><strong>Lỗi từ nhà sản xuất:</strong> TechShop chịu hoàn toàn chi phí vận chuyển</li>
                        <li><strong>Do đổi ý:</strong> Khách hàng chịu chi phí vận chuyển 2 chiều</li>
                        <li><strong>Giao nhầm hàng:</strong> TechShop chịu hoàn toàn chi phí vận chuyển</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3">Cần đổi trả sản phẩm?</h4>
                    <p class="mb-3">Liên hệ ngay để được hỗ trợ nhanh nhất</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="tel:1900xxxx" class="btn btn-light">
                            <i class="fas fa-phone-alt me-2"></i>1900 xxxx
                        </a>
                        <a href="/contact" class="btn btn-outline-light">
                            <i class="fas fa-envelope me-2"></i>Gửi yêu cầu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


