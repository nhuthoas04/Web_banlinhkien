<?php
/**
 * About Page - TechShop
 */

$pageTitle = 'Về chúng tôi';
$currentPage = 'about';

ob_start();
?>

<!-- Hero Section -->
<section class="about-hero py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Về TechShop</h1>
                <p class="lead mb-4">
                    Chúng tôi là đơn vị tiên phong trong lĩnh vực phân phối thiết bị công nghệ, 
                    máy tính và phụ kiện chính hãng tại Việt Nam với hơn 10 năm kinh nghiệm.
                </p>
                <div class="d-flex gap-4">
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">10+</h3>
                        <small>Năm kinh nghiệm</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">50K+</h3>
                        <small>Khách hàng</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0">100+</h3>
                        <small>Nhân viên</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="/assets/images/about-hero.jpg" alt="TechShop Office" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="/assets/images/our-story.jpg" alt="Our Story" class="img-fluid rounded-4">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Câu chuyện của chúng tôi</h2>
                <p class="text-muted mb-4">
                    Được thành lập từ năm 2014, TechShop bắt đầu từ một cửa hàng nhỏ với niềm đam mê 
                    công nghệ và mong muốn mang đến cho khách hàng những sản phẩm chất lượng nhất 
                    với giá cả hợp lý.
                </p>
                <p class="text-muted mb-4">
                    Qua hơn 10 năm phát triển, TechShop đã trở thành một trong những đơn vị hàng đầu 
                    trong lĩnh vực phân phối thiết bị công nghệ, với mạng lưới hơn 20 showroom 
                    trên toàn quốc và đội ngũ hơn 100 nhân viên chuyên nghiệp.
                </p>
                <p class="text-muted">
                    Chúng tôi tự hào là đối tác chính thức của các thương hiệu công nghệ hàng đầu 
                    như Apple, Dell, HP, ASUS, Lenovo, MSI, Acer...
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

<!-- Partners -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Đối tác chính thức</h2>
            <p class="text-muted">Chúng tôi là đại lý ủy quyền của các thương hiệu hàng đầu</p>
        </div>
        
        <div class="row align-items-center justify-content-center g-4">
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/dell.png" alt="Dell" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/hp.png" alt="HP" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/asus.png" alt="ASUS" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/lenovo.png" alt="Lenovo" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/msi.png" alt="MSI" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
            <div class="col-4 col-md-2">
                <img src="/assets/images/brands/acer.png" alt="Acer" class="img-fluid" style="filter: grayscale(100%); opacity: 0.7;">
            </div>
        </div>
    </div>
</section>

<!-- Team -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Đội ngũ lãnh đạo</h2>
            <p class="text-muted">Những người đứng sau thành công của TechShop</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="/assets/images/team/ceo.jpg" alt="CEO" 
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold mb-1">Nguyễn Văn A</h5>
                        <p class="text-primary mb-3">Giám đốc điều hành (CEO)</p>
                        <p class="text-muted small">
                            Hơn 15 năm kinh nghiệm trong lĩnh vực công nghệ và bán lẻ
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="/assets/images/team/cto.jpg" alt="CTO" 
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold mb-1">Trần Văn B</h5>
                        <p class="text-primary mb-3">Giám đốc công nghệ (CTO)</p>
                        <p class="text-muted small">
                            Chuyên gia về hệ thống và giải pháp công nghệ
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <img src="/assets/images/team/cmo.jpg" alt="CMO" 
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold mb-1">Lê Thị C</h5>
                        <p class="text-primary mb-3">Giám đốc marketing (CMO)</p>
                        <p class="text-muted small">
                            10 năm kinh nghiệm trong marketing và phát triển thương hiệu
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Bạn cần tư vấn?</h2>
        <p class="mb-4">Đội ngũ chuyên gia của chúng tôi luôn sẵn sàng hỗ trợ bạn</p>
        <a href="/contact" class="btn btn-light btn-lg rounded-pill px-5">
            <i class="fas fa-phone-alt me-2"></i>Liên hệ ngay
        </a>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>


