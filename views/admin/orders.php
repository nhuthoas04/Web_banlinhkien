<?php
$pageTitle = 'Quản lý đơn hàng';

// Define constants
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
            <h4>Quản lý đơn hàng</h4>
            <p><?= $totalOrders ?> đơn hàng</p>
        </div>
        <div class="header-right">
            <button class="btn btn-admin-outline" id="exportBtn">
                <i class="fas fa-download"></i> Xuất Excel
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col">
            <div class="order-stat-card pending">
                <i class="fas fa-clock"></i>
                <div class="stat-info">
                    <h3><?= $orderStats['pending'] ?? 0 ?></h3>
                    <span>Chờ xác nhận</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="order-stat-card confirmed">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <h3><?= $orderStats['confirmed'] ?? 0 ?></h3>
                    <span>Đã xác nhận</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="order-stat-card shipping">
                <i class="fas fa-truck"></i>
                <div class="stat-info">
                    <h3><?= $orderStats['shipping'] ?? 0 ?></h3>
                    <span>Đang giao</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="order-stat-card delivered">
                <i class="fas fa-box-open"></i>
                <div class="stat-info">
                    <h3><?= $orderStats['delivered'] ?? 0 ?></h3>
                    <span>Đã giao</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="order-stat-card cancelled">
                <i class="fas fa-times-circle"></i>
                <div class="stat-info">
                    <h3><?= $orderStats['cancelled'] ?? 0 ?></h3>
                    <span>Đã hủy</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-12">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Mã đơn, tên khách, SĐT..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <select class="form-select" name="status">
                            <option value="">Tất cả</option>
                            <option value="pending" <?= ($statusFilter ?? '') == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?= ($statusFilter ?? '') == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="shipping" <?= ($statusFilter ?? '') == 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                            <option value="delivered" <?= ($statusFilter ?? '') == 'delivered' ? 'selected' : '' ?>>Đã giao</option>
                            <option value="cancelled" <?= ($statusFilter ?? '') == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <select class="form-select" name="payment">
                            <option value="">Thanh toán</option>
                            <option value="cod">COD</option>
                            <option value="bank_transfer">Chuyển khoản</option>
                            <option value="momo">MoMo</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <input type="date" class="form-control" name="date_from" placeholder="Từ ngày">
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <input type="date" class="form-control" name="date_to" placeholder="Đến ngày">
                    </div>
                    <div class="col-lg-1 col-md-12">
                        <button type="submit" class="btn btn-admin-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="admin-table" id="ordersTable">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th width="150">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="item-select" value="<?= $order['id'] ?>">
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>admin?page=order-detail&id=<?= $order['id'] ?>" 
                                       class="order-code">#<?= $order['order_number'] ?? $order['id'] ?></a>
                                </td>
                                <td>
                                    <div class="customer-cell">
                                        <span class="name"><?= htmlspecialchars($order['customer_name'] ?? $order['user_name'] ?? '') ?></span>
                                        <span class="phone"><?= htmlspecialchars($order['customer_phone'] ?? '') ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="products-preview">
                                        <?php 
                                        $orderItems = $order['items'] ?? [];
                                        foreach (array_slice($orderItems, 0, 3) as $item): ?>
                                            <img src="<?= BASE_URL . ($item['product_image'] ?? $item['image'] ?? 'assets/images/no-image.jpg') ?>" 
                                                 alt="<?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? '') ?>" title="<?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? '') ?>">
                                        <?php endforeach; ?>
                                        <?php if (count($orderItems) > 3): ?>
                                            <span class="more">+<?= count($orderItems) - 3 ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="fw-bold text-danger"><?= formatPrice($order['total']) ?></td>
                                <td>
                                    <span class="payment-badge <?= $order['payment_method'] ?>">
                                        <?= $PAYMENT_METHODS[$order['payment_method']] ?? $order['payment_method'] ?>
                                    </span>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status-select" 
                                            data-id="<?= $order['id'] ?>">
                                        <?php foreach ($ORDER_STATUSES as $key => $label): ?>
                                            <option value="<?= $key ?>" <?= $order['status'] == $key ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?= formatDate($order['created_at']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>admin?page=order-detail&id=<?= $order['id'] ?>" 
                                           class="btn-icon" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn-icon print-btn" data-id="<?= $order['id'] ?>" title="In đơn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <?php if ($order['status'] == 'pending'): ?>
                                            <button class="btn-icon delete cancel-btn" data-id="<?= $order['id'] ?>" title="Hủy đơn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=orders&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<style>
/* Order Stat Cards */
.order-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    border-left: 4px solid;
}

.order-stat-card i {
    font-size: 28px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.order-stat-card.pending {
    border-color: #f59e0b;
}

.order-stat-card.pending i {
    background: #fef3c7;
    color: #f59e0b;
}

.order-stat-card.confirmed {
    border-color: #3b82f6;
}

.order-stat-card.confirmed i {
    background: #dbeafe;
    color: #3b82f6;
}

.order-stat-card.shipping {
    border-color: #8b5cf6;
}

.order-stat-card.shipping i {
    background: #ede9fe;
    color: #8b5cf6;
}

.order-stat-card.delivered {
    border-color: #10b981;
}

.order-stat-card.delivered i {
    background: #d1fae5;
    color: #10b981;
}

.order-stat-card.cancelled {
    border-color: #ef4444;
}

.order-stat-card.cancelled i {
    background: #fee2e2;
    color: #ef4444;
}

.stat-info h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.stat-info span {
    font-size: 13px;
    color: #64748b;
}

/* Customer Cell */
.customer-cell {
    display: flex;
    flex-direction: column;
}

.customer-cell .name {
    font-weight: 500;
}

.customer-cell .phone {
    font-size: 12px;
    color: #64748b;
}

/* Products Preview */
.products-preview {
    display: flex;
    align-items: center;
}

.products-preview img {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    object-fit: cover;
    margin-left: -10px;
    border: 2px solid #fff;
}

.products-preview img:first-child {
    margin-left: 0;
}

.products-preview .more {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    margin-left: -10px;
    border: 2px solid #fff;
}

/* Payment Badge */
.payment-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.payment-badge.cod {
    background: #fef3c7;
    color: #d97706;
}

.payment-badge.bank_transfer {
    background: #dbeafe;
    color: #2563eb;
}

.payment-badge.momo {
    background: #fce7f3;
    color: #db2777;
}

.payment-badge.vnpay {
    background: #ede9fe;
    color: #7c3aed;
}

/* Status Select */
.status-select {
    font-size: 13px;
    padding: 5px 10px;
    border-radius: 8px;
    min-width: 130px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status change
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.dataset.id;
            const status = this.value;
            
            fetch('<?= BASE_URL ?>api/admin/orders.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'update_status',
                    order_id: orderId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Đã cập nhật trạng thái đơn hàng');
                    // Reload page after short delay
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(data.message || 'Có lỗi xảy ra');
                    // Reset select to previous value if failed
                    location.reload();
                }
            })
            .catch(error => {
                toastr.error('Có lỗi xảy ra');
                location.reload();
            });
        });
    });
    
    // Cancel order
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            
            Swal.fire({
                title: 'Hủy đơn hàng?',
                text: 'Bạn có chắc muốn hủy đơn hàng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelOrder(orderId);
                }
            });
        });
    });
    
    function cancelOrder(orderId) {
        fetch('<?= BASE_URL ?>api/admin/orders.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'cancel',
                order_id: orderId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Đã hủy!', 'Đơn hàng đã được hủy.', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi!', data.message || 'Không thể hủy đơn hàng', 'error');
            }
        });
    }
    
    // Print order
    document.querySelectorAll('.print-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            window.open('<?= BASE_URL ?>admin?page=order-print&id=' + orderId, '_blank');
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


