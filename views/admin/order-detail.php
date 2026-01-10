<?php 
$pageTitle = 'Chi tiết đơn hàng #' . ($order['order_number'] ?? $order['id']);

$ORDER_STATUSES = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận',
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao',
    'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy'
];

$PAYMENT_METHODS = [
    'cod' => 'Thanh toán khi nhận hàng',
    'bank_transfer' => 'Chuyển khoản ngân hàng',
    'vnpay' => 'VNPay',
    'momo' => 'MoMo'
];

include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <a href="<?= BASE_URL ?>admin?page=orders" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <div>
                <h4 class="mb-0">Đơn hàng #<?= $order['order_number'] ?? $order['id'] ?></h4>
                <p class="mb-0 text-muted">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            </div>
        </div>
        <div class="header-right">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print"></i> In đơn hàng
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="admin-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Trạng thái đơn hàng</h5>
                        <select class="form-select w-auto status-select" data-id="<?= $order['id'] ?>" id="orderStatus">
                            <?php foreach ($ORDER_STATUSES as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $order['status'] == $key ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Status Timeline -->
                    <div class="order-timeline">
                        <?php 
                        $statusOrder = ['pending', 'confirmed', 'processing', 'shipping', 'delivered'];
                        $currentIndex = array_search($order['status'], $statusOrder);
                        if ($order['status'] === 'cancelled') $currentIndex = -1;
                        
                        foreach ($statusOrder as $index => $status): 
                            $isActive = $index <= $currentIndex;
                            $isCurrent = $index == $currentIndex;
                        ?>
                        <div class="timeline-item <?= $isActive ? 'active' : '' ?> <?= $isCurrent ? 'current' : '' ?>">
                            <div class="timeline-icon">
                                <?php if ($isActive): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    <i class="fas fa-circle"></i>
                                <?php endif; ?>
                            </div>
                            <span><?= $ORDER_STATUSES[$status] ?></span>
                        </div>
                        <?php if ($index < count($statusOrder) - 1): ?>
                        <div class="timeline-line <?= $index < $currentIndex ? 'active' : '' ?>"></div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Sản phẩm (<?= count($order['items'] ?? []) ?> sản phẩm)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $items = $order['items'] ?? [];
                                if (is_string($items)) $items = json_decode($items, true) ?? [];
                                
                                foreach ($items as $item): 
                                    $imgPath = $item['product_image'] ?? $item['image'] ?? '';
                                    if (empty($imgPath)) $imgPath = 'assets/images/no-image.jpg';
                                    $imageUrl = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $imageUrl ?>" 
                                                 alt="<?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? '') ?>" 
                                                 class="rounded me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0"><?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? '') ?></h6>
                                                <small class="text-muted">SKU: <?= $item['product_id'] ?? 'N/A' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end"><?= formatPrice($item['price']) ?></td>
                                    <td class="text-end fw-bold"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong> <?= htmlspecialchars($order['customer_name'] ?? $customer['name'] ?? 'N/A') ?></p>
                    <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($customer['email'] ?? 'N/A') ?></p>
                    <p class="mb-2"><strong>Điện thoại:</strong> <?= htmlspecialchars($order['customer_phone'] ?? $customer['phone'] ?? 'N/A') ?></p>
                    <hr>
                    <p class="mb-0"><strong>Địa chỉ giao hàng:</strong></p>
                    <p class="text-muted"><?= htmlspecialchars($order['shipping_address'] ?? 'N/A') ?></p>
                    <?php if (!empty($order['note'])): ?>
                    <hr>
                    <p class="mb-0"><strong>Ghi chú:</strong></p>
                    <p class="text-muted mb-0"><?= htmlspecialchars($order['note']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Thanh toán</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Phương thức:</strong> 
                        <span class="badge bg-info"><?= $PAYMENT_METHODS[$order['payment_method']] ?? $order['payment_method'] ?></span>
                    </p>
                    <p class="mb-0">
                        <strong>Trạng thái:</strong> 
                        <?php if ($order['payment_status'] ?? '' === 'paid'): ?>
                            <span class="badge bg-success">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Chưa thanh toán</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="admin-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Tổng cộng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span><?= formatPrice($order['subtotal'] ?? $order['total']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span><?= formatPrice($order['shipping_fee'] ?? 0) ?></span>
                    </div>
                    <?php if (!empty($order['discount'])): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span>-<?= formatPrice($order['discount']) ?></span>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong style="font-size: 1.1em;">Tổng cộng:</strong>
                        <strong class="text-danger" style="font-size: 1.25em;"><?= formatPrice($order['total']) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-timeline {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.timeline-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 12px;
}

.timeline-item.active .timeline-icon {
    background: #10b981;
    color: #fff;
}

.timeline-item.current .timeline-icon {
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
}

.timeline-item span {
    font-size: 12px;
    color: #64748b;
    text-align: center;
}

.timeline-item.active span {
    color: #10b981;
    font-weight: 500;
}

.timeline-line {
    flex: 1;
    height: 3px;
    background: #e2e8f0;
    margin: 0 -10px;
    margin-bottom: 30px;
}

.timeline-line.active {
    background: #10b981;
}

@media print {
    .admin-sidebar, .admin-header, .header-right, .status-select {
        display: none !important;
    }
    .admin-main {
        margin: 0 !important;
        padding: 20px !important;
    }
}
</style>

<script>
document.getElementById('orderStatus').addEventListener('change', function() {
    const orderId = this.dataset.id;
    const status = this.value;
    
    fetch('<?= BASE_URL ?>api/admin/orders.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'update-status',
            id: orderId,
            status: status,
            csrf_token: '<?= getToken() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công', 'Đã cập nhật trạng thái đơn hàng', 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
