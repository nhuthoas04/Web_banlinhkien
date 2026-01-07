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

    <!-- Status Tabs -->
    <div class="status-tabs mb-4">
        <a href="?page=reviews" class="tab-item <?= empty($statusFilter) ? 'active' : '' ?>">
            <i class="fas fa-list"></i> Tất cả
            <span class="count"><?= $reviewCounts['all'] ?? $totalReviews ?></span>
        </a>
        <a href="?page=reviews&status=pending" class="tab-item <?= $statusFilter == 'pending' ? 'active' : '' ?>">
            <i class="fas fa-clock"></i> Chờ duyệt
            <span class="count pending"><?= $reviewCounts['pending'] ?? 0 ?></span>
        </a>
        <a href="?page=reviews&status=approved" class="tab-item <?= $statusFilter == 'approved' ? 'active' : '' ?>">
            <i class="fas fa-check"></i> Đã duyệt
            <span class="count approved"><?= $reviewCounts['approved'] ?? 0 ?></span>
        </a>
        <a href="?page=reviews&status=rejected" class="tab-item <?= $statusFilter == 'rejected' ? 'active' : '' ?>">
            <i class="fas fa-times"></i> Từ chối
            <span class="count rejected"><?= $reviewCounts['rejected'] ?? 0 ?></span>
        </a>
    </div>

    <!-- Reviews List -->
    <div class="reviews-grid">
        <?php foreach ($reviews as $review): ?>
            <div class="review-card" data-id="<?= $review['id'] ?>">
                <div class="review-header">
                    <div class="user-info">
                        <img src="<?= BASE_URL . ($review['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                             alt="Avatar" class="avatar">
                        <div>
                            <span class="name"><?= htmlspecialchars($review['user']['name'] ?? $review['user_name'] ?? 'Ẩn danh') ?></span>
                            <span class="date"><?= formatDate($review['created_at']) ?></span>
                        </div>
                    </div>
                    <span class="status-badge <?= $review['status'] ?? 'pending' ?>">
                        <?php
                        $statusLabels = [
                            'pending' => 'Chờ duyệt',
                            'approved' => 'Đã duyệt',
                            'rejected' => 'Từ chối'
                        ];
                        echo $statusLabels[$review['status'] ?? 'pending'];
                        ?>
                    </span>
                </div>
                
                <div class="review-product">
                    <?php 
                    $imgPath = $review['product_image'] ?? 'assets/images/no-image.jpg';
                    $productImage = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                    ?>
                    <img src="<?= $productImage ?>" alt="">
                    <span><?= htmlspecialchars($review['product_name'] ?? 'Sản phẩm không tồn tại') ?></span>
                </div>
                
                <div class="review-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
                
                <div class="review-content">
                    <p><?= nl2br(htmlspecialchars($review['content'])) ?></p>
                </div>
                
                <?php if (!empty($review['images'])): ?>
                    <div class="review-images">
                        <?php foreach ($review['images'] as $image): ?>
                            <img src="<?= BASE_URL . $image ?>" alt="Review image" onclick="showImage(this.src)">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($review['reply'])): ?>
                    <div class="review-reply">
                        <strong><i class="fas fa-reply"></i> Phản hồi từ Shop:</strong>
                        <p><?= nl2br(htmlspecialchars($review['reply'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="review-actions">
                    <?php if (($review['status'] ?? 'pending') == 'pending'): ?>
                        <button class="btn btn-success btn-sm approve-btn" data-id="<?= $review['id'] ?>">
                            <i class="fas fa-check"></i> Duyệt
                        </button>
                        <button class="btn btn-danger btn-sm reject-btn" data-id="<?= $review['id'] ?>">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-outline-primary btn-sm reply-btn" data-id="<?= $review['id'] ?>"
                            data-reply="<?= htmlspecialchars($review['reply'] ?? '') ?>">
                        <i class="fas fa-reply"></i> <?= !empty($review['reply']) ? 'Sửa phản hồi' : 'Phản hồi' ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state col-12">
                <i class="fas fa-comments"></i>
                <h5>Không có đánh giá nào</h5>
                <p>Các đánh giá sẽ hiển thị ở đây khi có khách hàng gửi</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=reviews&status=<?= $statusFilter ?>&p=<?= $i ?>"><?= $i ?></a>
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
                        <textarea class="form-control" name="reply" id="replyContent" rows="4"></textarea>
                    </div>
                    <div class="quick-replies">
                        <small class="text-muted d-block mb-2">Phản hồi nhanh:</small>
                        <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                data-text="Cảm ơn bạn đã mua hàng và dành thời gian đánh giá sản phẩm!">
                            👍 Cảm ơn
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                data-text="Cảm ơn bạn đã phản hồi. Chúng tôi xin lỗi vì trải nghiệm chưa tốt và sẽ cải thiện dịch vụ.">
                            🙏 Xin lỗi
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary quick-reply" 
                                data-text="Vui lòng liên hệ hotline 1900xxxx để được hỗ trợ chi tiết hơn.">
                            📞 Liên hệ
                        </button>
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <img src="" alt="Review image" id="modalImage" class="img-fluid rounded">
        </div>
    </div>
</div>

<style>
/* Status Tabs */
.status-tabs {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.tab-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: #fff;
    border-radius: 10px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.tab-item:hover {
    color: #e53935;
}

.tab-item.active {
    background: #e53935;
    color: #fff;
}

.tab-item .count {
    background: #f1f5f9;
    padding: 2px 10px;
    border-radius: 50px;
    font-size: 12px;
}

.tab-item.active .count {
    background: rgba(255,255,255,0.2);
}

.tab-item .count.pending { color: #f59e0b; }
.tab-item .count.approved { color: #10b981; }
.tab-item .count.rejected { color: #ef4444; }
.tab-item.active .count.pending,
.tab-item.active .count.approved,
.tab-item.active .count.rejected { color: #fff; }

/* Reviews Grid */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.review-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.review-header .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-header .avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.review-header .name {
    display: block;
    font-weight: 500;
    color: #1e293b;
}

.review-header .date {
    font-size: 12px;
    color: #94a3b8;
}

.status-badge {
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

.review-product {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #f8fafc;
    border-radius: 8px;
    margin-bottom: 15px;
}

.review-product img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.review-product span {
    font-size: 14px;
    color: #64748b;
}

.review-rating {
    color: #fbbf24;
    margin-bottom: 10px;
}

.review-content p {
    margin: 0;
    color: #334155;
    line-height: 1.6;
}

.review-images {
    display: flex;
    gap: 10px;
    margin: 15px 0;
}

.review-images img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s;
}

.review-images img:hover {
    transform: scale(1.05);
}

.review-reply {
    background: #f0f9ff;
    padding: 12px;
    border-radius: 8px;
    margin: 15px 0;
    border-left: 3px solid #e53935;
}

.review-reply strong {
    color: #e53935;
    font-size: 13px;
}

.review-reply p {
    margin: 8px 0 0;
    font-size: 14px;
    color: #334155;
}

.review-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #f1f5f9;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
    grid-column: 1 / -1;
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
.quick-replies .quick-reply {
    margin-right: 5px;
    margin-bottom: 5px;
}

@media (max-width: 576px) {
    .reviews-grid {
        grid-template-columns: 1fr;
    }
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
        Swal.fire({
            title: status === 'approved' ? 'Duyệt đánh giá?' : 'Từ chối đánh giá?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: status === 'approved' ? 'Duyệt' : 'Từ chối',
            cancelButtonText: 'Hủy',
            confirmButtonColor: status === 'approved' ? '#10b981' : '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= BASE_URL ?>api/employee/reviews.php', {
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
                        Swal.fire('Thành công!', 'Đã cập nhật trạng thái', 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                    }
                });
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
        
        fetch('<?= BASE_URL ?>api/employee/reviews.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                action: 'reply',
                review_id: formData.get('review_id'),
                reply: formData.get('reply')
            })
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
});

// Show image modal
function showImage(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


