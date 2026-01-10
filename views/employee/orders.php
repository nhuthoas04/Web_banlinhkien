<?php
$pageTitle = 'Quản lý đơn hàng';
include __DIR__ . '/../layouts/employee-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý đơn hàng</h4>
            <p><?= $totalOrders ?> đơn hàng</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="status-tabs mb-4">
        <a href="?page=orders" class="tab-item <?= empty($statusFilter) ? 'active' : '' ?>">
            <span class="count"><?= $orderCounts['all'] ?? $totalOrders ?></span>
            Tất cả
        </a>
        <a href="?page=orders&status=pending" class="tab-item <?= $statusFilter == 'pending' ? 'active' : '' ?>">
            <span class="count pending"><?= $orderCounts['pending'] ?? 0 ?></span>
            Chờ xử lý
        </a>
        <a href="?page=orders&status=processing" class="tab-item <?= $statusFilter == 'processing' ? 'active' : '' ?>">
            <span class="count processing"><?= $orderCounts['processing'] ?? 0 ?></span>
            Đang xử lý
        </a>
        <a href="?page=orders&status=shipping" class="tab-item <?= $statusFilter == 'shipping' ? 'active' : '' ?>">
            <span class="count shipping"><?= $orderCounts['shipping'] ?? 0 ?></span>
            Đang giao
        </a>
        <a href="?page=orders&status=delivered" class="tab-item <?= $statusFilter == 'delivered' ? 'active' : '' ?>">
            <span class="count delivered"><?= $orderCounts['delivered'] ?? 0 ?></span>
            Đã giao
        </a>
        <a href="?page=orders&status=cancelled" class="tab-item <?= $statusFilter == 'cancelled' ? 'active' : '' ?>">
            <span class="count cancelled"><?= $orderCounts['cancelled'] ?? 0 ?></span>
            Đã hủy
        </a>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Mã đơn, tên KH, SĐT..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="payment">
                            <option value="">Thanh toán</option>
                            <option value="cod" <?= ($paymentFilter ?? '') == 'cod' ? 'selected' : '' ?>>COD</option>
                            <option value="bank" <?= ($paymentFilter ?? '') == 'bank' ? 'selected' : '' ?>>Chuyển khoản</option>
                            <option value="momo" <?= ($paymentFilter ?? '') == 'momo' ? 'selected' : '' ?>>MoMo</option>
                            <option value="vnpay" <?= ($paymentFilter ?? '') == 'vnpay' ? 'selected' : '' ?>>VNPay</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="date_from" 
                               value="<?= $dateFrom ?? '' ?>" placeholder="Từ ngày">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" name="date_to" 
                               value="<?= $dateTo ?? '' ?>" placeholder="Đến ngày">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-admin-primary w-100">
                            <i class="fas fa-search"></i> Tìm kiếm
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
                <table class="admin-table">
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
                            <th>Ngày tạo</th>
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
                                    <a href="?page=order-detail&id=<?= $order['id'] ?>" class="order-code">
                                        #<?= $order['order_number'] ?? $order['id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="customer-cell">
                                        <span class="name"><?= htmlspecialchars($order['customer_name'] ?? $order['user_name'] ?? '') ?></span>
                                        <small><?= htmlspecialchars($order['customer_phone'] ?? '') ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="products-cell">
                                        <?php 
                                        $orderItems = $order['items'] ?? [];
                                        $items = array_slice($orderItems, 0, 2);
                                        foreach ($items as $item): 
                                        ?>
                                            <div class="product-mini">
                                                <img src="<?= BASE_URL . ($item['product_image'] ?? $item['image'] ?? 'assets/images/no-image.jpg') ?>" alt="">
                                                <span><?= htmlspecialchars(mb_substr($item['product_name'] ?? $item['name'] ?? '', 0, 25)) ?>... x<?= $item['quantity'] ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (count($orderItems) > 2): ?>
                                            <small class="text-muted">+<?= count($orderItems) - 2 ?> sản phẩm khác</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="fw-bold text-danger"><?= formatPrice($order['total']) ?></td>
                                <td>
                                    <?php
                                    $paymentMethods = [
                                        'cod' => ['label' => 'COD', 'class' => 'secondary'],
                                        'bank' => ['label' => 'Chuyển khoản', 'class' => 'info'],
                                        'momo' => ['label' => 'MoMo', 'class' => 'danger'],
                                        'vnpay' => ['label' => 'VNPay', 'class' => 'primary']
                                    ];
                                    $pm = $paymentMethods[$order['payment_method']] ?? ['label' => $order['payment_method'], 'class' => 'secondary'];
                                    ?>
                                    <span class="badge bg-<?= $pm['class'] ?>"><?= $pm['label'] ?></span>
                                    <?php if ($order['payment_status'] == 'paid'): ?>
                                        <br><small class="text-success"><i class="fas fa-check"></i> Đã thanh toán</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status-select" 
                                            data-order-id="<?= $order['id'] ?>" 
                                            data-current-status="<?= $order['status'] ?>"
                                            <?= in_array($order['status'], ['delivered', 'cancelled']) ? 'disabled' : '' ?>>
                                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                        <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                        <option value="shipping" <?= $order['status'] == 'shipping' ? 'selected' : '' ?>>Đang giao</option>
                                        <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Đã giao</option>
                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                    </select>
                                </td>
                                <td>
                                    <small><?= formatDate($order['created_at']) ?></small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?page=order-detail&id=<?= $order['id'] ?>" 
                                           class="btn-icon" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn-icon print-btn" data-id="<?= $order['id'] ?>" title="In hóa đơn">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <?php if ($order['status'] == 'pending'): ?>
                                            <button class="btn-icon cancel-btn" data-id="<?= $order['id'] ?>" title="Hủy đơn">
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
                        <a class="page-link" href="?page=orders&status=<?= $statusFilter ?>&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đơn hàng #<span id="modalOrderCode"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<style>
/* Status Tabs */
.status-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.tab-item {
    display: flex;
    align-items: center;
    gap: 8px;
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
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    padding: 0 8px;
    background: #f1f5f9;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
}

.tab-item.active .count {
    background: rgba(255,255,255,0.2);
    color: #fff;
}

.tab-item .count.pending { color: #f59e0b; }
.tab-item .count.processing { color: #3b82f6; }
.tab-item .count.shipping { color: #8b5cf6; }
.tab-item .count.delivered { color: #10b981; }
.tab-item .count.cancelled { color: #ef4444; }

/* Customer Cell */
.customer-cell {
    display: flex;
    flex-direction: column;
}

.customer-cell .name {
    font-weight: 500;
    color: #1e293b;
}

.customer-cell small {
    color: #94a3b8;
}

/* Products Cell */
.products-cell {
    max-width: 250px;
}

.product-mini {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
}

.product-mini img {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 4px;
}

.product-mini span {
    font-size: 13px;
    color: #64748b;
}

/* Order Code */
.order-code {
    font-weight: 600;
    color: #e53935;
    text-decoration: none;
}

.order-code:hover {
    text-decoration: underline;
}

/* Status Select */
.status-select {
    min-width: 130px;
    font-size: 13px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status change
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const orderId = this.dataset.orderId;
            const status = this.value;
            const originalValue = this.dataset.currentStatus;
            
            Swal.fire({
                title: 'Cập nhật trạng thái?',
                text: `Đơn hàng sẽ được chuyển sang trạng thái "${this.options[this.selectedIndex].text}"`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/employee/orders.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'update_status',
                            order_id: orderId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success('Đã cập nhật trạng thái');
                            if (status === 'delivered' || status === 'cancelled') {
                                this.disabled = true;
                            }
                        } else {
                            this.value = originalValue;
                            Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                        }
                    });
                } else {
                    this.value = originalValue;
                }
            });
        });
    });
    
    // Cancel order
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            
            Swal.fire({
                title: 'Hủy đơn hàng?',
                text: 'Đơn hàng sẽ bị hủy và không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Đóng',
                input: 'textarea',
                inputLabel: 'Lý do hủy',
                inputPlaceholder: 'Nhập lý do hủy đơn...',
                inputValidator: (value) => {
                    if (!value) return 'Vui lòng nhập lý do hủy';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/employee/orders.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'cancel',
                            order_id: orderId,
                            reason: result.value
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
            });
        });
    });
    
    // Print order
    document.querySelectorAll('.print-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.id;
            window.open('<?= BASE_URL ?>print-order.php?id=' + orderId, '_blank', 'width=800,height=600');
        });
    });
    
    // Select all
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.item-select').forEach(cb => {
            cb.checked = this.checked;
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/employee-footer.php'; ?>


