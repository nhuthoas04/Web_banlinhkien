    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <!-- About -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <img src="<?= ASSETS_URL ?>/images/logo-white.png" alt="<?= SITE_NAME ?>">
                                <span>TechShop</span>
                            </div>
                            <p class="footer-desc">
                                Chuyên cung cấp các thiết bị và phụ kiện máy tính chính hãng với giá cả cạnh tranh nhất thị trường.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h5 class="footer-title">Liên kết</h5>
                            <ul class="footer-links">
                                <li><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                                <li><a href="<?= BASE_URL ?>products">Sản phẩm</a></li>
                                <li><a href="<?= BASE_URL ?>about">Giới thiệu</a></li>
                                <li><a href="<?= BASE_URL ?>contact">Liên hệ</a></li>
                                <li><a href="<?= BASE_URL ?>blog">Tin tức</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h5 class="footer-title">Danh mục</h5>
                            <ul class="footer-links">
                                <li><a href="#">Laptop</a></li>
                                <li><a href="#">PC Gaming</a></li>
                                <li><a href="#">Linh kiện</a></li>
                                <li><a href="#">Phụ kiện</a></li>
                                <li><a href="#">Màn hình</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="footer-widget">
                            <h5 class="footer-title">Liên hệ</h5>
                            <ul class="footer-contact">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= SITE_ADDRESS ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-phone-alt"></i>
                                    <span><?= SITE_PHONE ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <span><?= SITE_EMAIL ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-clock"></i>
                                    <span>8:00 - 21:00 (Thứ 2 - CN)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">
                            © <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="payment-methods">
                            <img src="<?= ASSETS_URL ?>/images/payment/visa.png" alt="Visa">
                            <img src="<?= ASSETS_URL ?>/images/payment/mastercard.png" alt="Mastercard">
                            <img src="<?= ASSETS_URL ?>/images/payment/momo.png" alt="Momo">
                            <img src="<?= ASSETS_URL ?>/images/payment/vnpay.png" alt="VNPay">
                            <img src="<?= ASSETS_URL ?>/images/payment/cod.png" alt="COD">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>
    
    <!-- Chat Box -->
    <?php if (isLoggedIn()): ?>
    <div class="chat-widget" id="chatWidget">
        <button class="chat-toggle" id="chatToggle">
            <i class="fas fa-comments"></i>
            <span class="chat-badge d-none">0</span>
        </button>
        <div class="chat-box d-none" id="chatBox">
            <div class="chat-header">
                <h6><i class="fas fa-headset"></i> Hỗ trợ trực tuyến</h6>
                <button class="chat-close" id="chatClose"><i class="fas fa-times"></i></button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="chat-welcome">
                    <img src="<?= ASSETS_URL ?>/images/support.png" alt="Support">
                    <p>Xin chào! Chúng tôi có thể giúp gì cho bạn?</p>
                </div>
            </div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Nhập tin nhắn...">
                <button id="chatSend"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem nhanh sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <!-- Product content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Base URL for JS - defined before main.js loads -->
    <script>
        var BASE_URL = '<?= BASE_URL ?>';
        var ASSETS_URL = '<?= ASSETS_URL ?>';
    </script>
    
    <!-- Custom JS -->
    <script src="<?= ASSETS_URL ?>/js/main.js?v=20260107"></script>
    
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?= ASSETS_URL ?>/js/<?= $js ?>?v=20260107"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // Hide preloader when page is loaded
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 300);
            }
        });
        
        // Fallback: hide preloader after 3 seconds anyway
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 3000);
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>


