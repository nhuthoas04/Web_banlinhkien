<?php
$pageTitle = 'Dashboard Nhân viên';
include __DIR__ . '/../layouts/employee-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Xin chào, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Nhân viên') ?>!</h4>
            <p>Chào mừng bạn trở lại. Đây là tổng quan công việc hôm nay.</p>
        </div>
        <div class="header-right">
            <span class="text-muted"><?= date('l, d/m/Y') ?></span>
        </div>
    </div>

    <!-- Today Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="employee-stat-card orders">
                <div class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="info">
                    <h3><?= $todayOrders ?? 0 ?></h3>
                    <span>Đơn hàng hôm nay</span>
                </div>
                <div class="trend up">
                    <i class="fas fa-arrow-up"></i> <?= $orderGrowth ?? 0 ?>%
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="employee-stat-card pending">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info">
                    <h3><?= $pendingOrders ?? 0 ?></h3>
                    <span>Đơn chờ xử lý</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="employee-stat-card chats">
                <div class="icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="info">
                    <h3><?= $pendingChats ?? 0 ?></h3>
                    <span>Tin nhắn chưa đọc</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="employee-stat-card reviews">
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="info">
                    <h3><?= $pendingReviews ?? 0 ?></h3>
                    <span>Đánh giá chờ duyệt</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Orders -->
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6><i class="fas fa-shopping-bag me-2"></i>Đơn hàng cần xử lý</h6>
                    <a href="?page=orders" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPendingOrders as $order): ?>
                                    <tr>
                                        <td>
                                            <a href="?page=order-detail&id=<?= $order['id'] ?>" class="order-code">
                                                #<?= $order['order_code'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="customer-cell">
                                                <span><?= htmlspecialchars($order['customer_name']) ?></span>
                                                <small><?= $order['customer_phone'] ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= count($order['items']) ?> sản phẩm
                                            </span>
                                        </td>
                                        <td class="fw-bold"><?= formatPrice($order['total']) ?></td>
                                        <td>
                                            <small class="text-muted"><?= timeAgo($order['created_at']) ?></small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-success process-btn" 
                                                        data-id="<?= $order['id'] ?>" title="Xác nhận">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <a href="?page=order-detail&id=<?= $order['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($recentPendingOrders)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                                            Không có đơn hàng cần xử lý
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Chats -->
            <div class="admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6><i class="fas fa-comments me-2"></i>Tin nhắn gần đây</h6>
                    <a href="?page=chat" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="chat-list">
                        <?php foreach ($recentChats as $chat): ?>
                            <a href="?page=chat&id=<?= $chat['id'] ?>" class="chat-item <?= ($chat['unread_count'] ?? 0) > 0 ? 'unread' : '' ?>">
                                <img src="<?= BASE_URL . ($chat['user']['avatar'] ?? 'assets/images/default-avatar.svg') ?>" 
                                     alt="Avatar" class="avatar">
                                <div class="chat-info">
                                    <div class="chat-header">
                                        <span class="name"><?= htmlspecialchars($chat['user']['name'] ?? $chat['user_name'] ?? 'Khách') ?></span>
                                        <span class="time"><?= timeAgo($chat['last_message_at'] ?? '') ?></span>
                                    </div>
                                    <p class="last-message"><?= htmlspecialchars(mb_substr($chat['last_message'] ?? 'Chưa có tin nhắn', 0, 50)) ?>...</p>
                                </div>
                                <?php if (($chat['unread_count'] ?? 0) > 0): ?>
                                    <span class="unread-badge"><?= $chat['unread_count'] ?? 0 ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                        
                        <?php if (empty($recentChats)): ?>
                            <div class="empty-state py-4">
                                <i class="fas fa-comments fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có tin nhắn nào</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h6><i class="fas fa-bolt me-2"></i>Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <a href="?page=orders&status=pending" class="quick-action-btn">
                            <i class="fas fa-clock"></i>
                            <span>Đơn chờ xử lý</span>
                        </a>
                        <a href="?page=orders&status=processing" class="quick-action-btn">
                            <i class="fas fa-box"></i>
                            <span>Đơn đang giao</span>
                        </a>
                        <a href="?page=chat" class="quick-action-btn">
                            <i class="fas fa-headset"></i>
                            <span>Hỗ trợ chat</span>
                        </a>
                        <a href="?page=reviews" class="quick-action-btn">
                            <i class="fas fa-star"></i>
                            <span>Duyệt đánh giá</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Performance -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h6><i class="fas fa-chart-line me-2"></i>Hiệu suất của tôi</h6>
                </div>
                <div class="card-body">
                    <div class="performance-stats">
                        <div class="perf-item">
                            <div class="perf-label">Đơn đã xử lý hôm nay</div>
                            <div class="perf-value"><?= $myProcessedOrders ?? 0 ?></div>
                        </div>
                        <div class="perf-item">
                            <div class="perf-label">Đơn đã xử lý tuần này</div>
                            <div class="perf-value"><?= $myWeeklyOrders ?? 0 ?></div>
                        </div>
                        <div class="perf-item">
                            <div class="perf-label">Đánh giá đã duyệt</div>
                            <div class="perf-value"><?= $myApprovedReviews ?? 0 ?></div>
                        </div>
                        <div class="perf-item">
                            <div class="perf-label">Tin nhắn đã trả lời</div>
                            <div class="perf-value"><?= $myRepliedChats ?? 0 ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6><i class="fas fa-star me-2"></i>Đánh giá mới</h6>
                    <a href="?page=reviews" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="review-list">
                        <?php foreach ($recentReviews as $review): ?>
                            <div class="review-item-mini">
                                <div class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="review-text"><?= htmlspecialchars(mb_substr($review['content'], 0, 60)) ?>...</p>
                                <div class="review-meta">
                                    <span><?= htmlspecialchars($review['user']['username'] ?? 'Ẩn danh') ?></span>
                                    <span><?= timeAgo($review['created_at']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Employee Stat Cards */
.employee-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.employee-stat-card .icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.employee-stat-card.orders .icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.employee-stat-card.pending .icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: #fff;
}

.employee-stat-card.chats .icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: #fff;
}

.employee-stat-card.reviews .icon {
    background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);
    color: #fff;
}

.employee-stat-card .info h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.employee-stat-card .info span {
    font-size: 14px;
    color: #64748b;
}

.employee-stat-card .trend {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 50px;
}

.employee-stat-card .trend.up {
    background: #d1fae5;
    color: #059669;
}

/* Customer Cell */
.customer-cell {
    display: flex;
    flex-direction: column;
}

.customer-cell small {
    color: #94a3b8;
}

.order-code {
    font-weight: 600;
    color: #e53935;
    text-decoration: none;
}

.order-code:hover {
    text-decoration: underline;
}

/* Chat List */
.chat-list {
    max-height: 400px;
    overflow-y: auto;
}

.chat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px 20px;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.3s;
}

.chat-item:hover {
    background: #f8fafc;
}

.chat-item.unread {
    background: #fef2f2;
}

.chat-item .avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-item .chat-info {
    flex: 1;
    min-width: 0;
}

.chat-item .chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.chat-item .name {
    font-weight: 500;
    color: #1e293b;
}

.chat-item .time {
    font-size: 12px;
    color: #94a3b8;
}

.chat-item .last-message {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-item .unread-badge {
    background: #e53935;
    color: #fff;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 50px;
    font-weight: 600;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    text-decoration: none;
    color: #64748b;
    transition: all 0.3s;
}

.quick-action-btn:hover {
    background: #e53935;
    color: #fff;
}

.quick-action-btn i {
    font-size: 24px;
}

.quick-action-btn span {
    font-size: 13px;
    text-align: center;
}

/* Performance Stats */
.performance-stats {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.perf-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f5f9;
}

.perf-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.perf-label {
    font-size: 14px;
    color: #64748b;
}

.perf-value {
    font-size: 20px;
    font-weight: 700;
    color: #e53935;
}

/* Review List Mini */
.review-list {
    max-height: 300px;
    overflow-y: auto;
}

.review-item-mini {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
}

.review-item-mini:last-child {
    border-bottom: none;
}

.review-item-mini .review-stars {
    color: #fbbf24;
    margin-bottom: 8px;
    font-size: 12px;
}

.review-item-mini .review-text {
    margin: 0 0 8px;
    font-size: 14px;
    color: #334155;
}

.review-item-mini .review-meta {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #94a3b8;
}

/* Empty State */
.empty-state {
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Process order (confirm)
    document.querySelectorAll('.process-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            
            Swal.fire({
                title: 'Xác nhận đơn hàng?',
                text: 'Đơn hàng sẽ được chuyển sang trạng thái đang xử lý',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/employee/orders.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'update_status',
                            order_id: orderId,
                            status: 'processing'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Thành công', 'Đã xác nhận đơn hàng', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                        }
                    });
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/employee-footer.php'; ?>


