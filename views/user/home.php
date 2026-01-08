<?php 
$pageTitle = 'Trang chủ - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php'; 
?>

<!-- Hero Banner -->
<section class="hero-section">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="<?= BASE_URL ?>san-pham?category=gaming" class="hero-slide-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/thang_04_pc_banner_web_collection_1920x420.jpg" alt="PC Gaming Collection" class="hero-banner-img">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="<?= BASE_URL ?>san-pham?sale=1" class="hero-slide-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-pc-gvn-rtx-5060-t9-header-banner.png" alt="RTX 5060" class="hero-banner-img">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="<?= BASE_URL ?>san-pham?category=laptop" class="hero-slide-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-laptop-gaming-t8-header-banner.png" alt="Laptop Gaming" class="hero-banner-img">
                </a>
            </div>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>

<!-- Small Banners Grid -->
<section class="small-banners-section">
    <div class="container-fluid p-3">
        <div class="row g-3">
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=pc" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-build-pc-slider-right-t8.png" alt="Build PC" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=pc-amd" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-pc-amd-sub-t8.png" alt="PC AMD" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=laptop-gaming" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-laptop-gaming-slider-bot-t8.png" alt="Laptop Gaming" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=laptop-van-phong" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-laptop-van-phong-slider-bot-t8.png" alt="Laptop Văn Phòng" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=ban-phim" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-ban-phim-slider-right-t8.png" alt="Bàn Phím" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=gaming-gear" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-gaming-gear-sub-t8.png" alt="Gaming Gear" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?category=man-hinh" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-man-hinh-sub-t8.png" alt="Màn Hình" class="small-banner-img">
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-6">
                <a href="<?= BASE_URL ?>san-pham?sale=1" class="small-banner-link">
                    <img src="<?= ASSETS_URL ?>/images/banners/gearvn-gaming-gear-deal-hoi-sub-banner-t8.png" alt="Gaming Gear Deal" class="small-banner-img">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Miễn phí vận chuyển</h5>
                        <p>Đơn hàng từ 500K</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Bảo hành chính hãng</h5>
                        <p>Từ 12 đến 36 tháng</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Đổi trả dễ dàng</h5>
                        <p>Trong vòng 7 ngày</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="feature-content">
                        <h5>Hỗ trợ 24/7</h5>
                        <p>Tư vấn nhiệt tình</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Danh mục sản phẩm</h2>
            <p class="section-subtitle">Khám phá các danh mục phổ biến</p>
        </div>
        
        <div class="row">
            <?php foreach ($categories as $index => $category): ?>
            <div class="col-lg-2 col-md-4 col-6 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                <a href="<?= BASE_URL ?>products?category=<?= $category['id'] ?>" class="category-card">
                    <div class="category-icon">
                        <?php
                        // Map tên danh mục với file ảnh
                        $imageMap = [
                            'Laptop' => 'Laptop.png',
                            'PC Gaming' => 'PC.png',
                            'Linh kien may tinh' => 'linhkien.png',
                            'Man hinh' => 'Manhinh.jpg',
                            'Ban phim' => 'Banphim.jpg',
                            'Chuot' => 'Chuot.jpg',
                            'Tai nghe' => 'Tainghe.jpg',
                            'Phu kien' => 'Phukien.png'
                        ];
                        $imageName = $imageMap[$category['name']] ?? 'Laptop.png';
                        ?>
                        <img src="<?= BASE_URL ?>img-danhmuc/<?= $imageName ?>" alt="<?= $category['name'] ?>" class="category-image">
                    </div>
                    <h6 class="category-name"><?= $category['name'] ?></h6>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="products-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <a href="<?= BASE_URL ?>products?featured=1" class="section-link">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row">
            <?php foreach ($featuredProducts as $index => $product): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?= ($index % 4) * 100 ?>">
                <?php include __DIR__ . '/../components/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Banner Ads -->
<section class="banner-ads-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4" data-aos="fade-right">
                <a href="#" class="banner-ad">
                    <img src="<?= BASE_URL ?>img-banner-gaming/1646813329_slider_1.jpg" alt="Banner" class="img-fluid rounded-4">
                    <div class="banner-overlay">
                        <span class="banner-label">Hot Deal</span>
                        <h3>Laptop Gaming</h3>
                        <p>Giảm đến 20%</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-4" data-aos="fade-left">
                <a href="#" class="banner-ad">
                    <img src="<?= BASE_URL ?>img-banner-gaming/gaming-computer-black-friday-super-sale-social-media-post-design-template_1101054-31250.avif" alt="Banner" class="img-fluid rounded-4">
                    <div class="banner-overlay">
                        <span class="banner-label">Mới</span>
                        <h3>Phụ kiện Gaming</h3>
                        <p>Freeship toàn quốc</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- New Products -->
<section class="products-section bg-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Sản phẩm mới</h2>
            <a href="<?= BASE_URL ?>products?sort=newest" class="section-link">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="swiper products-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($newProducts as $product): ?>
                <div class="swiper-slide">
                    <?php include __DIR__ . '/../components/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Best Selling -->
<?php if (!empty($bestSellingProducts)): ?>
<section class="products-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Bán chạy nhất</h2>
            <a href="<?= BASE_URL ?>products?sort=best_selling" class="section-link">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="row">
            <?php foreach ($bestSellingProducts as $index => $product): ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?= ($index % 4) * 100 ?>">
                <?php include __DIR__ . '/../components/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sale Products -->
<?php if (!empty($saleProducts)): ?>
<section class="products-section sale-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="sale-header">
                <h2 class="section-title"><i class="fas fa-fire text-danger"></i> Flash Sale</h2>
                <div class="countdown" id="saleCountdown">
                    <div class="countdown-item">
                        <span class="countdown-value" id="hours">00</span>
                        <span class="countdown-label">Giờ</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="minutes">00</span>
                        <span class="countdown-label">Phút</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-value" id="seconds">00</span>
                        <span class="countdown-label">Giây</span>
                    </div>
                </div>
            </div>
            <a href="<?= BASE_URL ?>products?sale=1" class="section-link">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="swiper sale-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($saleProducts as $product): ?>
                <div class="swiper-slide">
                    <?php include __DIR__ . '/../components/product-card.php'; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Brands -->
<section class="brands-section bg-light">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Thương hiệu nổi bật</h2>
        </div>
        
        <div class="swiper brands-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php 
                $brands = [
                    'asus' => 'logo-asus.jpg',
                    'msi' => 'logo-msi.jpg',
                    'gigabyte' => 'logo-gigabyte.webp',
                    'corsair' => 'logo-corsair.png',
                    'logitech' => 'logo-logitech.png',
                    'razer' => 'logo-razer.png',
                    'steelseries' => 'logo-SteelSeries.jpg',
                    'hyperx' => 'logo-HyperX.webp'
                ];
                foreach ($brands as $brand => $logo): 
                ?>
                <div class="swiper-slide">
                    <a href="<?= BASE_URL ?>products?brand=<?= $brand ?>" class="brand-item">
                        <img src="<?= ASSETS_URL ?>/images/brands/<?= $logo ?>" alt="<?= ucfirst($brand) ?>">
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Initialize Swipers
document.addEventListener('DOMContentLoaded', function() {
    // Hero Swiper
    new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    
    // Products Swiper
    new Swiper('.products-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 }
        }
    });
    
    // Sale Swiper
    new Swiper('.sale-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 }
        }
    });
    
    // Brands Swiper
    new Swiper('.brands-swiper', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        breakpoints: {
            576: { slidesPerView: 4 },
            768: { slidesPerView: 5 },
            992: { slidesPerView: 6 },
            1200: { slidesPerView: 8 }
        }
    });
    
    // Countdown Timer
    function updateCountdown() {
        const now = new Date();
        const endOfDay = new Date();
        endOfDay.setHours(23, 59, 59, 999);
        
        const diff = endOfDay - now;
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
        document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


