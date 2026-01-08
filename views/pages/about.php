<?php
/**
 * About Page - TechShop
 */

$pageTitle = 'Giới thiệu - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php';
?>

<!-- Hero Section -->
<section class="about-hero py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); margin-top: -1px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold mb-4">Về TechShop</h1>
                <p class="lead mb-4">
                    Chúng tôi là đơn vị tiên phong trong lĩnh vực phân phối thiết bị công nghệ, 
                    máy tính và phụ kiện chính hãng tại Việt Nam.
                </p>
                <div class="d-flex justify-content-center gap-5 mt-4">
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">1+</h3>
                        <small>Năm kinh nghiệm</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">5K+</h3>
                        <small>Khách hàng</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">1000+</h3>
                        <small>Sản phẩm</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <h2 class="fw-bold mb-3">Câu chuyện của chúng tôi</h2>
                <p class="text-muted mb-4">
                    Được thành lập từ năm 2025, TechShop bắt đầu từ một cửa hàng nhỏ với niềm đam mê 
                    công nghệ và mong muốn mang đến cho khách hàng những sản phẩm chất lượng nhất 
                    với giá cả hợp lý.
                </p>
                <p class="text-muted mb-4">
                    Với sự phát triển không ngừng, TechShop đã trở thành một trong những đơn vị uy tín 
                    trong lĩnh vực phân phối thiết bị công nghệ, với đội ngũ nhân viên chuyên nghiệp
                    và tận tâm.
                </p>
                <p class="text-muted">
                    Chúng tôi tự hào là đối tác chính thức của các thương hiệu công nghệ hàng đầu 
                    như Dell, HP, ASUS, Lenovo, MSI, Acer...
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Giá trị cốt lõi</h2>
            <p class="text-muted">Những giá trị định hướng mọi hoạt động của chúng tôi</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-gem fa-2x"></i>
                        </div>
                        <h5 class="fw-bold">Chất lượng</h5>
                        <p class="text-muted mb-0">
                            100% sản phẩm chính hãng, có nguồn gốc xuất xứ rõ ràng
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                        <h5 class="fw-bold">Uy tín</h5>
                        <p class="text-muted mb-0">
                            Cam kết bảo hành đúng hạn, đổi trả minh bạch
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <h5 class="fw-bold">Tận tâm</h5>
                        <p class="text-muted mb-0">
                            Đội ngũ tư vấn nhiệt tình, hỗ trợ khách hàng 24/7
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3" 
                             style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-lightbulb fa-2x"></i>
                        </div>
                        <h5 class="fw-bold">Sáng tạo</h5>
                        <p class="text-muted mb-0">
                            Không ngừng đổi mới để mang đến trải nghiệm tốt nhất
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tại sao chọn TechShop?</h2>
            <p class="text-muted">Những lý do khách hàng tin tưởng chúng tôi</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Sản phẩm chính hãng 100%</h5>
                        <p class="text-muted">Tất cả sản phẩm đều được nhập từ các nhà phân phối chính thức, có đầy đủ giấy tờ và bảo hành.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Giao hàng nhanh chóng</h5>
                        <p class="text-muted">Giao hàng toàn quốc trong 1-3 ngày. Miễn phí vận chuyển cho đơn hàng từ 500.000đ.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Bảo hành uy tín</h5>
                        <p class="text-muted">Chính sách bảo hành lên đến 36 tháng. Hỗ trợ 1 đổi 1 trong 30 ngày nếu lỗi nhà sản xuất.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            <i class="fas fa-headset"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold">Hỗ trợ tận tâm</h5>
                        <p class="text-muted">Đội ngũ tư vấn chuyên nghiệp, sẵn sàng hỗ trợ 24/7 qua hotline, chat và email.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Địa chỉ</h5>
                        <p class="text-muted mb-0"><?= SITE_ADDRESS ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-phone-alt fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Hotline</h5>
                        <p class="text-muted mb-0"><?= SITE_PHONE ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Email</h5>
                        <p class="text-muted mb-0"><?= SITE_EMAIL ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">Bạn cần tư vấn?</h2>
        <p class="mb-4">Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
        <a href="<?= BASE_URL ?>lien-he" class="btn btn-light btn-lg rounded-pill px-5">
            <i class="fas fa-phone-alt me-2"></i>Liên hệ ngay
        </a>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
