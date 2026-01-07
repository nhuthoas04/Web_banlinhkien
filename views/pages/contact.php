<?php
/**
 * Contact Page - TechShop
 */

$pageTitle = 'Liên hệ';
$currentPage = 'contact';

ob_start();
?>

<!-- Hero Section -->
<section class="contact-hero py-5 bg-light">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold mb-3">Liên hệ với chúng tôi</h1>
            <p class="text-muted lead">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn
            </p>
        </div>
    </div>
</section>

<!-- Contact Info & Form -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4">Thông tin liên hệ</h3>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Địa chỉ</h5>
                        <p class="text-muted mb-0">
                            123 Đường Nguyễn Văn Linh<br>
                            Quận 7, TP. Hồ Chí Minh
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone-alt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Hotline</h5>
                        <p class="text-muted mb-1">
                            <a href="tel:1900xxxx" class="text-decoration-none">1900 xxxx</a> (8:00 - 22:00)
                        </p>
                        <p class="text-muted mb-0">
                            <a href="tel:0901234567" class="text-decoration-none">090 123 4567</a> (Hỗ trợ kỹ thuật)
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Email</h5>
                        <p class="text-muted mb-1">
                            <a href="mailto:support@techshop.vn" class="text-decoration-none">support@techshop.vn</a>
                        </p>
                        <p class="text-muted mb-0">
                            <a href="mailto:sales@techshop.vn" class="text-decoration-none">sales@techshop.vn</a>
                        </p>
                    </div>
                </div>
                
                <div class="contact-item d-flex mb-4">
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Giờ làm việc</h5>
                        <p class="text-muted mb-1">
                            Thứ 2 - Thứ 6: 8:00 - 21:00
                        </p>
                        <p class="text-muted mb-0">
                            Thứ 7 - Chủ nhật: 9:00 - 18:00
                        </p>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="social-links mt-4">
                    <h5 class="fw-bold mb-3">Kết nối với chúng tôi</h5>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success rounded-circle" style="width: 45px; height: 45px;">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>
                        
                        <form id="contactForm" action="/api/contact.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Chủ đề</label>
                                    <select name="subject" class="form-select">
                                        <option value="general">Câu hỏi chung</option>
                                        <option value="order">Hỏi về đơn hàng</option>
                                        <option value="product">Tư vấn sản phẩm</option>
                                        <option value="warranty">Bảo hành</option>
                                        <option value="complaint">Khiếu nại</option>
                                        <option value="partnership">Hợp tác kinh doanh</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" rows="5" required 
                                              placeholder="Nhập nội dung tin nhắn của bạn..."></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="fw-bold mb-4 text-center">Vị trí cửa hàng</h3>
        <div class="ratio ratio-21x9 rounded-4 overflow-hidden shadow-sm">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.024969813181!2d106.6970946!3d10.7295636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f8c2d0e2d53%3A0x4c8a5b6f0d7f0d7!2zMTIzIMSQxrDhu51uZyBOZ3V54buFbiBWxINuIExpbmgsIFTDom4gUGjDuiwgUXXhuq1uIDcsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1609459200000!5m2!1svi!2s"
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- Showrooms -->
<section class="py-5">
    <div class="container">
        <h3 class="fw-bold mb-4 text-center">Hệ thống showroom</h3>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-store me-2"></i>TechShop Quận 1
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            456 Nguyễn Huệ, Quận 1, TP.HCM
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2"></i>
                            028 1234 5678
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            8:00 - 22:00
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-store me-2"></i>TechShop Quận 7
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            123 Nguyễn Văn Linh, Quận 7, TP.HCM
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2"></i>
                            028 8765 4321
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            8:00 - 22:00
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-primary mb-3">
                            <i class="fas fa-store me-2"></i>TechShop Hà Nội
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            789 Phố Huế, Hai Bà Trưng, Hà Nội
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-phone me-2"></i>
                            024 1122 3344
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            8:00 - 22:00
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Câu hỏi thường gặp</h3>
            <p class="text-muted">Những thắc mắc phổ biến của khách hàng</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Tôi có thể đặt hàng online và nhận tại showroom không?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Có, bạn hoàn toàn có thể đặt hàng online và chọn nhận tại showroom gần nhất. 
                                Chúng tôi sẽ giữ sản phẩm cho bạn trong 3 ngày sau khi đặt hàng.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Chính sách bảo hành như thế nào?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Tất cả sản phẩm tại TechShop đều được bảo hành chính hãng theo quy định của nhà sản xuất. 
                                Bạn có thể mang sản phẩm đến bất kỳ showroom nào của chúng tôi để được hỗ trợ.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                TechShop có hỗ trợ trả góp không?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Có, chúng tôi hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng hoặc trả góp qua các công ty 
                                tài chính với thủ tục đơn giản, duyệt nhanh trong 15 phút.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Thời gian giao hàng mất bao lâu?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Đối với nội thành TP.HCM và Hà Nội: 1-2 giờ với dịch vụ giao nhanh, 1 ngày với giao tiêu chuẩn. 
                                Các tỉnh thành khác: 2-4 ngày làm việc.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Gửi thành công!',
                text: 'Chúng tôi sẽ phản hồi sớm nhất có thể.',
                confirmButtonColor: '#e63946'
            });
            this.reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
                confirmButtonColor: '#e63946'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Có lỗi xảy ra, vui lòng thử lại.',
            confirmButtonColor: '#e63946'
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>


