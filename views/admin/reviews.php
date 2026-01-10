<?php
$pageTitle = 'Quản lý đánh giá';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý đánh giá</h4>
            <p><?= $totalReviews ?> đánh giá</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>admin?page=reviews&rating=5" class="review-stat-card <?= ($ratingFilter ?? '') == '5' ? 'active' : '' ?>" style="text-decoration: none; display: block;">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <div class="count"><?= $reviewStats[5] ?? 0 ?></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>admin?page=reviews&rating=4" class="review-stat-card <?= ($ratingFilter ?? '') == '4' ? 'active' : '' ?>" style="text-decoration: none; display: block;">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                </div>
                <div class="count"><?= $reviewStats[4] ?? 0 ?></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>admin?page=reviews&rating=3" class="review-stat-card <?= ($ratingFilter ?? '') == '3' ? 'active' : '' ?>" style="text-decoration: none; display: block;">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                </div>
                <div class="count"><?= $reviewStats[3] ?? 0 ?></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>admin?page=reviews&rating=2" class="review-stat-card <?= ($ratingFilter ?? '') == '2' ? 'active' : '' ?>" style="text-decoration: none; display: block;">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                </div>
                <div class="count"><?= $reviewStats[2] ?? 0 ?></div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?= BASE_URL ?>admin?page=reviews&rating=1" class="review-stat-card <?= ($ratingFilter ?? '') == '1' ? 'active' : '' ?>" style="text-decoration: none; display: block;">
                <div class="stars">
                    <i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>
                </div>
                <div class="count"><?= $reviewStats[1] ?? 0 ?></div>
            </a>
        </div>
        <div class="col-md-2">
            <div class="review-stat-card highlight">
                <div class="avg-rating">
                    <span><?= number_format($avgRating ?? 0, 1) ?></span>
                    <i class="fas fa-star"></i>
                </div>
                <div class="label">Điểm TB</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form" method="GET" action="<?= BASE_URL ?>admin">
                <input type="hidden" name="page" value="reviews">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Tìm theo nội dung, sản phẩm..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="rating">
                            <option value="">Tất cả đánh giá</option>
                            <option value="5" <?= ($ratingFilter ?? '') == '5' ? 'selected' : '' ?>>5 sao</option>
                            <option value="4" <?= ($ratingFilter ?? '') == '4' ? 'selected' : '' ?>>4 sao</option>
                            <option value="3" <?= ($ratingFilter ?? '') == '3' ? 'selected' : '' ?>>3 sao</option>
                            <option value="2" <?= ($ratingFilter ?? '') == '2' ? 'selected' : '' ?>>2 sao</option>
                            <option value="1" <?= ($ratingFilter ?? '') == '1' ? 'selected' : '' ?>>1 sao</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?= ($statusFilter ?? '') == 'pending' ? 'selected' : '' ?>>Chờ duyệt</option>
                            <option value="approved" <?= ($statusFilter ?? '') == 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                            <option value="rejected" <?= ($statusFilter ?? '') == 'rejected' ? 'selected' : '' ?>>Đã từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="has_images">
                            <option value="">Tất cả</option>
                            <option value="1" <?= ($hasImages ?? '') == '1' ? 'selected' : '' ?>>Có hình ảnh</option>
                            <option value="0" <?= ($hasImages ?? '') == '0' ? 'selected' : '' ?>>Không có hình</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-admin-primary flex-fill">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="bulkApprove">
                                <i class="fas fa-check-double"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="admin-card">
        <div class="card-body p-0">
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item" data-id="<?= $review['id'] ?>">
                        <div class="review-checkbox">
                            <input type="checkbox" class="item-select" value="<?= $review['id'] ?>">
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <div class="user-info">
                                    <img src="<?= BASE_URL . ($review['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                                         alt="Avatar" class="avatar">
                                    <div>
                                        <span class="username"><?= htmlspecialchars($review['user']['name'] ?? $review['user_name'] ?? 'Ẩn danh') ?></span>
                                        <span class="date"><?= formatDate($review['created_at']) ?></span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="review-product">
                                <a href="<?= BASE_URL ?>admin?page=products&id=<?= $review['product_id'] ?>" target="_blank">
                                    <?php 
                                    $imgPath = $review['product_image'] ?? 'assets/images/no-image.jpg';
                                    $productImage = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                                    ?>
                                    <img src="<?= $productImage ?>" alt="">
                                    <span><?= htmlspecialchars($review['product_name'] ?? 'Sản phẩm không tồn tại') ?></span>
                                </a>
                            </div>
                            
                            <div class="review-text">
                                <?= nl2br(htmlspecialchars($review['content'])) ?>
                            </div>
                            
                            <?php if (!empty($review['images'])): ?>
                                <div class="review-images">
                                    <?php foreach ($review['images'] as $image): ?>
                                        <a href="<?= BASE_URL . $image ?>" data-lightbox="review-<?= $review['id'] ?>">
                                            <img src="<?= BASE_URL . $image ?>" alt="Review image">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($review['reply'])): ?>
                                <div class="review-reply">
                                    <i class="fas fa-reply"></i>
                                    <div>
                                        <strong>Phản hồi từ Shop:</strong>
                                        <p><?= nl2br(htmlspecialchars($review['reply'])) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="review-actions">
                            <span class="status-badge <?= $review['status'] ?? 'pending' ?>">
                                <?php
                                $statusLabels = [
                                    'pending' => 'Chờ duyệt',
                                    'approved' => 'Đã duyệt',
                                    'rejected' => 'Đã từ chối'
                                ];
                                echo $statusLabels[$review['status'] ?? 'pending'];
                                ?>
                            </span>
                            <div class="action-buttons mt-2">
                                <?php if (($review['status'] ?? 'pending') == 'pending'): ?>
                                    <button class="btn btn-sm btn-success approve-btn" data-id="<?= $review['id'] ?>" title="Duyệt">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger reject-btn" data-id="<?= $review['id'] ?>" title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-outline-primary reply-btn" data-id="<?= $review['id'] ?>" 
                                        data-reply="<?= htmlspecialchars($review['reply'] ?? '') ?>" title="Phản hồi">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $review['id'] ?>" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($reviews)): ?>
                    <div class="empty-state">
                        <i class="fas fa-comments"></i>
                        <h5>Chưa có đánh giá nào</h5>
                        <p>Các đánh giá từ khách hàng sẽ hiển thị ở đây</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=reviews&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Phản hồi đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="replyForm">
                <input type="hidden" name="review_id" id="replyReviewId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nội dung phản hồi</label>
                        <textarea class="form-control" name="reply" id="replyContent" rows="4" 
                                  placeholder="Nhập nội dung phản hồi..."></textarea>
                    </div>
                    <div class="quick-replies">
                        <label class="form-label">Phản hồi nhanh:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                    data-text="Cảm ơn bạn đã mua hàng và dành thời gian đánh giá sản phẩm!">
                                Cảm ơn
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                    data-text="Cảm ơn bạn đã phản hồi. Chúng tôi sẽ cải thiện dịch vụ tốt hơn.">
                                Xin lỗi
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                    data-text="Vui lòng liên hệ hotline 1900xxxx để được hỗ trợ chi tiết.">
                                Liên hệ hỗ trợ
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Gửi phản hồi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Review Stat Cards */
.review-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.review-stat-card .stars {
    color: #fbbf24;
    margin-bottom: 10px;
}

.review-stat-card .count {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
}

.review-stat-card.highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.review-stat-card.highlight .avg-rating {
    font-size: 32px;
    font-weight: 700;
}

.review-stat-card.highlight .avg-rating i {
    color: #fbbf24;
    margin-left: 5px;
}

.review-stat-card.highlight .label {
    font-size: 14px;
    opacity: 0.9;
}

/* Reviews List */
.reviews-list {
    padding: 0;
}

.review-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.3s;
}

.review-item:hover {
    background-color: #f8fafc;
}

.review-checkbox {
    padding-top: 5px;
}

.review-content {
    flex: 1;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.review-header .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-header .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.review-header .username {
    display: block;
    font-weight: 500;
    color: #1e293b;
}

.review-header .date {
    font-size: 12px;
    color: #94a3b8;
}

.review-rating {
    color: #fbbf24;
}

.review-product {
    margin-bottom: 10px;
}

.review-product a {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: #f1f5f9;
    border-radius: 8px;
    text-decoration: none;
    color: #64748b;
    font-size: 14px;
    transition: background-color 0.3s;
}

.review-product a:hover {
    background: #e2e8f0;
}

.review-product img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

.review-text {
    color: #334155;
    line-height: 1.6;
    margin-bottom: 10px;
}

.review-images {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.review-images img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s;
}

.review-images img:hover {
    transform: scale(1.05);
}

.review-reply {
    display: flex;
    gap: 10px;
    padding: 12px;
    background: #f0f9ff;
    border-radius: 8px;
    border-left: 3px solid #e53935;
}

.review-reply i {
    color: #e53935;
    margin-top: 4px;
}

.review-reply strong {
    color: #e53935;
    display: block;
    margin-bottom: 5px;
}

.review-reply p {
    margin: 0;
    color: #334155;
    font-size: 14px;
}

.review-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    min-width: 100px;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.approved {
    background: #d1fae5;
    color: #059669;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #dc2626;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.empty-state i {
    font-size: 60px;
    margin-bottom: 20px;
}

.empty-state h5 {
    color: #64748b;
    margin-bottom: 10px;
}

/* Quick Replies */
.quick-replies {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.quick-reply:hover {
    background: #e53935;
    border-color: #e53935;
    color: #fff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve review
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.dataset.id;
            updateReviewStatus(reviewId, 'approved');
        });
    });
    
    // Reject review
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.dataset.id;
            updateReviewStatus(reviewId, 'rejected');
        });
    });
    
    function updateReviewStatus(reviewId, status) {
        fetch('<?= BASE_URL ?>api/admin/reviews.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                action: 'update_status',
                review_id: reviewId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        });
    }
    
    // Reply button
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('replyReviewId').value = this.dataset.id;
            document.getElementById('replyContent').value = this.dataset.reply || '';
            new bootstrap.Modal(document.getElementById('replyModal')).show();
        });
    });
    
    // Quick reply
    document.querySelectorAll('.quick-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('replyContent').value = this.dataset.text;
        });
    });
    
    // Reply form
    document.getElementById('replyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'reply');
        
        fetch('<?= BASE_URL ?>api/admin/reviews.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã gửi phản hồi', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        });
    });
    
    // Delete review
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const reviewId = this.dataset.id;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: 'Đánh giá sẽ bị xóa vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/admin/reviews.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'delete',
                            review_id: reviewId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Đánh giá đã được xóa.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Không thể xóa đánh giá', 'error');
                        }
                    });
                }
            });
        });
    });
    
    // Bulk approve
    document.getElementById('bulkApprove').addEventListener('click', function() {
        const selected = document.querySelectorAll('.item-select:checked');
        if (selected.length === 0) {
            Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một đánh giá', 'info');
            return;
        }
        
        const ids = Array.from(selected).map(cb => cb.value);
        
        Swal.fire({
            title: 'Duyệt hàng loạt?',
            text: `Duyệt ${ids.length} đánh giá đã chọn?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= BASE_URL ?>api/admin/reviews.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'bulk_approve',
                        review_ids: ids
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Thành công', 'Đã duyệt các đánh giá', 'success')
                        .then(() => location.reload());
                    }
                });
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


