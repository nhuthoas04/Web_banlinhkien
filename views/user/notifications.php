<?php
$pageTitle = 'Thông báo';
$currentPage = 'notifications';
include __DIR__ . '/../layouts/header.php';
?>

<section class="notifications-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Notifications Content -->
            <div class="col-lg-9">
                <div class="notifications-container">
                    <div class="section-header">
                        <h4><i class="fas fa-bell"></i> Thông báo</h4>
                    </div>
                    
                    <div class="notifications-content p-4">
                        <?php if (empty($notifications)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-bell fa-4x text-muted mb-3"></i>
                                <h5>Chưa có thông báo nào</h5>
                                <p class="text-muted">Bạn sẽ nhận được thông báo khi có cập nhật về đơn hàng, khuyến mãi...</p>
                            </div>
                        <?php else: ?>
                            <div class="notifications-list">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="notification-item p-3 mb-2 border-bottom <?= $notification['is_read'] ? '' : 'bg-light' ?>">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="notification-icon">
                                                <i class="fas fa-<?= $notification['icon'] ?? 'info-circle' ?> text-primary"></i>
                                            </div>
                                            <div class="notification-body flex-grow-1">
                                                <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                                                <p class="mb-1 text-muted"><?= htmlspecialchars($notification['message']) ?></p>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
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
.notifications-container {
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

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e3f2fd;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
