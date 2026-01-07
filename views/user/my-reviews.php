<?php
$pageTitle = 'Đánh giá của tôi';
$currentPage = 'reviews';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active">Đánh giá của tôi</li>
            </ol>
        </nav>
    </div>
</div>

<section class="reviews-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Reviews Content -->
            <div class="col-lg-9">
                <div class="reviews-container">
                    <div class="section-header">
                        <h4><i class="fas fa-star"></i> Đánh giá của tôi</h4>
                    </div>
                    
                    <div class="reviews-content p-4">
                        <?php if (empty($userReviews)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                                <h5>Chưa có đánh giá nào</h5>
                                <p class="text-muted">Hãy mua sắm và đánh giá sản phẩm để chia sẻ trải nghiệm của bạn!</p>
                                <a href="<?= BASE_URL ?>don-hang" class="btn btn-primary mt-3">
                                    <i class="fas fa-shopping-bag"></i> Xem đơn hàng
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="reviews-list">
                                <?php foreach ($userReviews as $review): ?>
                                    <div class="review-item mb-4 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($review['product_name'] ?? 'Sản phẩm') ?></h6>
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <span class="badge bg-<?= $review['status'] === 'approved' ? 'success' : ($review['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                                <?= $review['status'] === 'approved' ? 'Đã duyệt' : ($review['status'] === 'pending' ? 'Chờ duyệt' : 'Từ chối') ?>
                                            </span>
                                        </div>
                                        <p class="mb-2"><?= htmlspecialchars($review['content'] ?? '') ?></p>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.reviews-container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

.section-header {
    padding: 20px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header h4 i {
    color: var(--primary-color);
}

.review-item {
    background: #f8fafc;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
