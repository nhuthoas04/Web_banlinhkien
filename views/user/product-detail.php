<?php
$pageTitle = htmlspecialchars($product['name']);
include __DIR__ . '/../layouts/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL ?>?page=products">Sản phẩm</a>
                </li>
                <?php if ($category): ?>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>?page=products&category=<?= $category['slug'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail Section -->
<section class="product-detail py-5">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-5">
                <div class="product-gallery">
                    <div class="main-image-container">
                        <?php if (!empty($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                            <span class="badge-sale">-<?= $product['discount_percent'] ?>%</span>
                        <?php endif; ?>
                        <?php 
                        $firstImage = $product['images'][0] ?? null;
                        $mainImage = is_array($firstImage) ? ($firstImage['image_url'] ?? '') : ($firstImage ?? '');
                        if (empty($mainImage)) $mainImage = 'assets/images/no-image.jpg';
                        $mainImageUrl = (strpos($mainImage, 'http') === 0) ? $mainImage : BASE_URL . $mainImage;
                        ?>
                        <img src="<?= $mainImageUrl ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="main-image" id="mainImage">
                        <button class="zoom-btn" id="zoomBtn">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                    
                    <?php if (count($product['images'] ?? []) > 1): ?>
                        <div class="thumbnail-gallery">
                            <?php foreach ($product['images'] as $index => $img): ?>
                                <?php 
                                $imgUrl = is_array($img) ? ($img['image_url'] ?? '') : ($img ?? '');
                                if (empty($imgUrl)) continue;
                                $imageUrl = (strpos($imgUrl, 'http') === 0) ? $imgUrl : BASE_URL . $imgUrl;
                                ?>
                                <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>" 
                                     data-image="<?= $imageUrl ?>">
                                    <img src="<?= $imageUrl ?>" alt="Thumbnail">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-7">
                <div class="product-info">
                    <div class="product-brand"><?= htmlspecialchars($product['brand'] ?? 'TechShop') ?></div>
                    <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <span class="rating-stars">
                                <?php 
                                $rating = $product['rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++): 
                                    if ($i <= floor($rating)): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php elseif ($i - 0.5 <= $rating): ?>
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif;
                                endfor; ?>
                            </span>
                            <span class="rating-value"><?= number_format($rating, 1) ?></span>
                            <span class="rating-count">(<?= $product['review_count'] ?? 0 ?> đánh giá)</span>
                        </div>
                        <div class="meta-item">
                            <span class="sold-count">Đã bán: <?= $product['sold_count'] ?? 0 ?></span>
                        </div>
                    </div>

                    <div class="product-price-box">
                        <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                            <span class="current-price"><?= formatPrice($product['sale_price']) ?></span>
                            <span class="original-price"><?= formatPrice($product['price']) ?></span>
                            <span class="discount-badge">
                                Tiết kiệm <?= formatPrice($product['price'] - $product['sale_price']) ?>
                            </span>
                        <?php else: ?>
                            <span class="current-price"><?= formatPrice($product['price']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Short Description -->
                    <?php if (!empty($product['short_description'])): ?>
                        <div class="product-short-desc">
                            <?= nl2br(htmlspecialchars($product['short_description'])) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Stock Status -->
                    <div class="stock-status">
                        <?php if (($product['stock'] ?? 0) > 0): ?>
                            <span class="in-stock">
                                <i class="fas fa-check-circle"></i> Còn hàng 
                                (<?= $product['stock'] ?> sản phẩm)
                            </span>
                        <?php else: ?>
                            <span class="out-stock">
                                <i class="fas fa-times-circle"></i> Hết hàng
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Add to Cart -->
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn minus" id="qtyMinus">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="qty-input" id="quantity" value="1" min="1" 
                                   max="<?= $product['stock'] ?? 1 ?>">
                            <button type="button" class="qty-btn plus" id="qtyPlus">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <button type="button" class="btn btn-add-cart" id="addToCart"
                                data-product-id="<?= $product['id'] ?>"
                                <?= ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-cart-plus"></i>
                            <span>Thêm vào giỏ</span>
                        </button>
                        
                        <button type="button" class="btn btn-buy-now" id="buyNow"
                                data-product-id="<?= $product['id'] ?>"
                                <?= ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-bolt"></i>
                            <span>Mua ngay</span>
                        </button>
                    </div>



                    <!-- Policies -->
                    <div class="product-policies">
                        <div class="policy-item">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Miễn phí vận chuyển</strong>
                                <span>Đơn hàng từ 500.000đ</span>
                            </div>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Bảo hành chính hãng</strong>
                                <span>12 - 36 tháng</span>
                            </div>
                        </div>
                        <div class="policy-item">
                            <i class="fas fa-sync-alt"></i>
                            <div>
                                <strong>Đổi trả miễn phí</strong>
                                <span>Trong 7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="product-tabs mt-5">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button">
                        <i class="fas fa-file-alt"></i> Mô tả sản phẩm
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="specs-tab" data-bs-toggle="tab" 
                            data-bs-target="#specifications" type="button">
                        <i class="fas fa-list"></i> Thông số kỹ thuật
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button">
                        <i class="fas fa-star"></i> Đánh giá (<?= $product['review_count'] ?? 0 ?>)
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="productTabContent">
                <!-- Description -->
                <div class="tab-pane fade show active" id="description">
                    <div class="tab-body">
                        <?php if (!empty($product['description'])): ?>
                            <div class="product-description">
                                <?= $product['description'] ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Chưa có mô tả chi tiết cho sản phẩm này.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Specifications -->
                <div class="tab-pane fade" id="specifications">
                    <div class="tab-body">
                        <?php if (!empty($product['specifications'])): ?>
                            <div class="specifications-content">
                                <?= $product['specifications'] ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Chưa có thông số kỹ thuật cho sản phẩm này.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="tab-pane fade" id="reviews">
                    <div class="tab-body">
                        <!-- Reviews Summary -->
                        <div class="reviews-summary">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="rating-overview">
                                        <div class="rating-big"><?= number_format($product['rating'] ?? 0, 1) ?></div>
                                        <div class="rating-stars">
                                            <?php 
                                            $rating = $product['rating'] ?? 0;
                                            for ($i = 1; $i <= 5; $i++): 
                                                if ($i <= floor($rating)): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php elseif ($i - 0.5 <= $rating): ?>
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-warning"></i>
                                                <?php endif;
                                            endfor; ?>
                                        </div>
                                        <div class="rating-total"><?= $product['review_count'] ?? 0 ?> đánh giá</div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="rating-bars">
                                        <?php for ($i = 5; $i >= 1; $i--): 
                                            $count = $ratingCounts[$i] ?? 0;
                                            $percent = ($product['review_count'] ?? 0) > 0 
                                                ? ($count / $product['review_count']) * 100 : 0;
                                        ?>
                                            <div class="rating-bar-item">
                                                <span class="star-label"><?= $i ?> sao</span>
                                                <div class="progress-bar-wrapper">
                                                    <div class="progress-bar-fill" style="width: <?= $percent ?>%"></div>
                                                </div>
                                                <span class="count-label"><?= $count ?></span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Form -->
                        <?php if (isLoggedIn()): ?>
                            <div class="review-form-section">
                                <h5>Viết đánh giá của bạn</h5>
                                <form id="reviewForm" class="review-form">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    
                                    <div class="rating-select mb-3">
                                        <label>Đánh giá:</label>
                                        <div class="star-rating">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                                                <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <textarea name="content" class="form-control" rows="4" 
                                                  placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="review-login-prompt">
                                <p>Vui lòng <a href="<?= BASE_URL ?>?page=login">đăng nhập</a> để viết đánh giá.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Reviews List -->
                        <div class="reviews-list" id="reviewsList">
                            <?php if (!empty($reviews)): ?>
                                <?php foreach ($reviews as $review): ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <img src="<?= BASE_URL ?>assets/images/default-avatar.svg" 
                                                     alt="Avatar" class="reviewer-avatar">
                                                <div>
                                                    <div class="reviewer-name">
                                                        <?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?>
                                                    </div>
                                                    <div class="review-date">
                                                        <?= formatDate($review['created_at']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-rating">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <?= nl2br(htmlspecialchars($review['content'])) ?>
                                        </div>
                                        <?php if (!empty($review['reply'])): ?>
                                            <div class="review-reply">
                                                <div class="reply-header">
                                                    <i class="fas fa-store"></i>
                                                    <span>Phản hồi từ TechShop</span>
                                                </div>
                                                <div class="reply-content">
                                                    <?= nl2br(htmlspecialchars($review['reply'])) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-reviews">
                                    <i class="fas fa-comment-slash"></i>
                                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products mt-5">
                <h3 class="section-title">Sản phẩm liên quan</h3>
                <div class="row">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="col-lg-3 col-md-4 col-6">
                            <?php $product = $relatedProduct; include __DIR__ . '/../components/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Image Zoom Modal -->
<div class="modal fade" id="imageZoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <img src="" alt="Zoom" class="img-fluid" id="zoomImage">
            </div>
        </div>
    </div>
</div>

<style>
/* Product Gallery */
.product-gallery {
    position: sticky;
    top: 100px;
}

.main-image-container {
    position: relative;
    background: #f8f9fa;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 15px;
}

.main-image {
    width: 100%;
    height: 450px;
    object-fit: contain;
    cursor: zoom-in;
}

.badge-sale {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ef4444;
    color: #fff;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
}

.zoom-btn {
    position: absolute;
    bottom: 15px;
    right: 15px;
    width: 45px;
    height: 45px;
    background: #fff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    cursor: pointer;
}

.thumbnail-gallery {
    display: flex;
    gap: 10px;
    overflow-x: auto;
}

.thumbnail-item {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s;
}

.thumbnail-item:hover,
.thumbnail-item.active {
    border-color: var(--primary-color);
}

.thumbnail-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info {
    padding-left: 30px;
}

.product-brand {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.product-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 15px;
    line-height: 1.3;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.rating-value {
    font-weight: 600;
    color: #f59e0b;
}

.rating-count,
.sold-count {
    color: #64748b;
}

.product-price-box {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 20px 25px;
    border-radius: 15px;
    margin-bottom: 25px;
}

.current-price {
    font-size: 32px;
    font-weight: 700;
    color: #dc2626;
}

.original-price {
    font-size: 18px;
    color: #94a3b8;
    text-decoration: line-through;
    margin-left: 15px;
}

.discount-badge {
    display: inline-block;
    background: #dc2626;
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    margin-left: 15px;
}

.product-short-desc {
    color: #64748b;
    font-size: 15px;
    line-height: 1.7;
    margin-bottom: 20px;
}

.quick-specs {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.quick-specs h6 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 15px;
}

.quick-specs ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.quick-specs li {
    display: flex;
    padding: 8px 0;
    border-bottom: 1px dashed #e2e8f0;
    font-size: 14px;
}

.quick-specs li:last-child {
    border-bottom: none;
}

.quick-specs .spec-name {
    color: #64748b;
    width: 140px;
    flex-shrink: 0;
}

.quick-specs .spec-value {
    font-weight: 500;
    color: #1e293b;
}

.stock-status {
    margin-bottom: 25px;
}

.in-stock {
    color: #10b981;
    font-weight: 500;
}

.out-stock {
    color: #ef4444;
    font-weight: 500;
}

/* Product Actions */
.product-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.qty-btn {
    width: 45px;
    height: 50px;
    border: none;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.3s;
}

.qty-btn:hover {
    background: var(--primary-color);
    color: #fff;
}

.qty-input {
    width: 60px;
    height: 50px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
}

.qty-input:focus {
    outline: none;
}

.btn-add-cart {
    flex: 1;
    padding: 15px 30px;
    background: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-add-cart:hover {
    background: #1557b0;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(26, 115, 232, 0.4);
}

.btn-buy-now {
    padding: 15px 30px;
    background: #f97316;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-buy-now:hover {
    background: #ea580c;
}

/* Extra Actions */
.extra-actions {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #e2e8f0;
}

.extra-actions button {
    background: none;
    border: none;
    color: #64748b;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.extra-actions button:hover {
    color: var(--primary-color);
}

/* Policies */
.product-policies {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.policy-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
}

.policy-item i {
    font-size: 24px;
    color: var(--primary-color);
}

.policy-item strong {
    display: block;
    font-size: 13px;
    color: #1e293b;
    margin-bottom: 3px;
}

.policy-item span {
    font-size: 12px;
    color: #64748b;
}

/* Product Tabs */
.product-tabs .nav-tabs {
    border: none;
    background: #fff;
    border-radius: 16px 16px 0 0;
    padding: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.product-tabs .nav-link {
    border: none;
    padding: 18px 30px;
    font-size: 15px;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 3px solid transparent;
}

.product-tabs .nav-link.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.product-tabs .tab-content {
    background: #fff;
    border-radius: 0 0 16px 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.tab-body {
    padding: 30px;
}

.product-description {
    line-height: 1.8;
    color: #374151;
}

.product-description img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 15px 0;
}

/* Specs Table */
.specs-table {
    width: 100%;
}

.specs-table tr:nth-child(odd) {
    background: #f8fafc;
}

.specs-table td {
    padding: 15px 20px;
}

.specs-table .spec-name {
    font-weight: 500;
    color: #64748b;
    width: 200px;
}

.specs-table .spec-value {
    color: #1e293b;
}

/* Reviews */
.reviews-summary {
    background: #f8fafc;
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
}

.rating-overview {
    text-align: center;
}

.rating-big {
    font-size: 56px;
    font-weight: 700;
    color: #f59e0b;
    line-height: 1;
}

.rating-overview .rating-stars {
    font-size: 24px;
    margin: 10px 0;
}

.rating-total {
    color: #64748b;
}

.rating-bars {
    padding-left: 30px;
}

.rating-bar-item {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 12px;
}

.star-label {
    width: 50px;
    font-size: 14px;
    color: #64748b;
}

.progress-bar-wrapper {
    flex: 1;
    height: 10px;
    background: #e2e8f0;
    border-radius: 5px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: #f59e0b;
    border-radius: 5px;
}

.count-label {
    width: 30px;
    font-size: 14px;
    color: #64748b;
}

/* Review Form */
.review-form-section {
    background: #f8fafc;
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
}

.review-form-section h5 {
    margin-bottom: 20px;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    font-size: 28px;
    color: #e2e8f0;
    transition: all 0.2s;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #f59e0b;
}

/* Review Item */
.review-item {
    padding: 25px 0;
    border-bottom: 1px solid #e2e8f0;
}

.review-item:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.reviewer-name {
    font-weight: 600;
    color: #1e293b;
}

.review-date {
    font-size: 13px;
    color: #94a3b8;
}

.review-rating i {
    font-size: 14px;
}

.review-content {
    color: #374151;
    line-height: 1.7;
}

.review-reply {
    background: #f0fdf4;
    padding: 15px 20px;
    border-radius: 12px;
    margin-top: 15px;
    border-left: 4px solid #10b981;
}

.reply-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #10b981;
    margin-bottom: 8px;
}

.reply-content {
    color: #374151;
}

.no-reviews {
    text-align: center;
    padding: 50px;
    color: #94a3b8;
}

.no-reviews i {
    font-size: 48px;
    margin-bottom: 15px;
}

/* Related Products */
.related-products {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 25px;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 60px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 2px;
}

/* Image Modal */
#imageZoomModal .modal-content {
    background: transparent;
    border: none;
}

#imageZoomModal .btn-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: #fff;
    border-radius: 50%;
    padding: 10px;
}

/* Responsive */
@media (max-width: 991px) {
    .product-info {
        padding-left: 0;
        margin-top: 30px;
    }
    
    .product-policies {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .product-title {
        font-size: 22px;
    }
    
    .current-price {
        font-size: 26px;
    }
    
    .product-actions {
        flex-wrap: wrap;
    }
    
    .quantity-selector {
        width: 100%;
    }
    
    .btn-add-cart,
    .btn-buy-now {
        flex: 1;
        min-width: 45%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image gallery
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            mainImage.src = this.dataset.image;
        });
    });
    
    // Image zoom
    document.getElementById('zoomBtn').addEventListener('click', function() {
        document.getElementById('zoomImage').src = mainImage.src;
        new bootstrap.Modal(document.getElementById('imageZoomModal')).show();
    });
    
    // Quantity selector
    const qtyInput = document.getElementById('quantity');
    const maxQty = parseInt(qtyInput.max);
    
    document.getElementById('qtyMinus').addEventListener('click', function() {
        let value = parseInt(qtyInput.value) || 1;
        if (value > 1) {
            qtyInput.value = value - 1;
        }
    });
    
    document.getElementById('qtyPlus').addEventListener('click', function() {
        let value = parseInt(qtyInput.value) || 1;
        if (value < maxQty) {
            qtyInput.value = value + 1;
        }
    });
    
    // Add to cart
    document.getElementById('addToCart').addEventListener('click', function() {
        const productId = this.dataset.productId;
        const quantity = parseInt(qtyInput.value) || 1;
        
        addToCart(productId, quantity);
    });
    
    // Buy now
    document.getElementById('buyNow').addEventListener('click', function() {
        const productId = this.dataset.productId;
        const quantity = parseInt(qtyInput.value) || 1;
        
        addToCart(productId, quantity, true);
    });
    
    // Review form
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('<?= BASE_URL ?>api/reviews.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Đánh giá của bạn đã được gửi và đang chờ duyệt.',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
                    });
                }
            });
        });
    }
});

function addToCart(productId, quantity, redirect = false) {
    fetch('<?= BASE_URL ?>api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'add',
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (redirect) {
                window.location.href = '<?= BASE_URL ?>?page=checkout';
            } else {
                updateCartCount(data.cart_count);
                Swal.fire({
                    icon: 'success',
                    title: 'Đã thêm vào giỏ hàng!',
                    showCancelButton: true,
                    confirmButtonText: 'Xem giỏ hàng',
                    cancelButtonText: 'Tiếp tục mua',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= BASE_URL ?>?page=cart';
                    }
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
            });
        }
    });
}

function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


