<?php
$pageTitle = 'Dashboard';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-info">
                    <h3><?= number_format($stats['total_orders'] ?? 0) ?></h3>
                    <p>Tổng đơn hàng</p>
                    <span class="change up">
                        <i class="fas fa-arrow-up"></i> <?= $stats['order_change'] ?? 0 ?>% so với tháng trước
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-info">
                    <h3><?= formatPrice($stats['total_revenue'] ?? 0) ?></h3>
                    <p>Doanh thu</p>
                    <span class="change up">
                        <i class="fas fa-arrow-up"></i> <?= $stats['revenue_change'] ?? 0 ?>% so với tháng trước
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-info">
                    <h3><?= number_format($stats['total_users'] ?? 0) ?></h3>
                    <p>Khách hàng</p>
                    <span class="change up">
                        <i class="fas fa-arrow-up"></i> <?= $stats['user_change'] ?? 0 ?>% so với tháng trước
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stats-info">
                    <h3><?= number_format($stats['total_products'] ?? 0) ?></h3>
                    <p>Sản phẩm</p>
                    <span class="change">
                        <?= $stats['low_stock'] ?? 0 ?> sản phẩm sắp hết
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8">
            <div class="admin-card">
                <div class="card-header">
                    <h5>Doanh thu</h5>
                    <div class="chart-period">
                        <button class="btn btn-sm btn-outline-secondary active" data-period="week">Tuần</button>
                        <button class="btn btn-sm btn-outline-secondary" data-period="month">Tháng</button>
                        <button class="btn btn-sm btn-outline-secondary" data-period="year">Năm</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-xl-4">
            <div class="admin-card">
                <div class="card-header">
                    <h5>Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="status-legend mt-4">
                        <div class="legend-item">
                            <span class="dot pending"></span>
                            <span>Chờ xử lý: <?= $orderStats['pending'] ?? 0 ?></span>
                        </div>
                        <div class="legend-item">
                            <span class="dot confirmed"></span>
                            <span>Đã xác nhận: <?= $orderStats['confirmed'] ?? 0 ?></span>
                        </div>
                        <div class="legend-item">
                            <span class="dot shipping"></span>
                            <span>Đang giao: <?= $orderStats['shipping'] ?? 0 ?></span>
                        </div>
                        <div class="legend-item">
                            <span class="dot delivered"></span>
                            <span>Đã giao: <?= $orderStats['delivered'] ?? 0 ?></span>
                        </div>
                        <div class="legend-item">
                            <span class="dot cancelled"></span>
                            <span>Đã hủy: <?= $orderStats['cancelled'] ?? 0 ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Recent Orders -->
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5>Đơn hàng gần đây</h5>
                    <a href="<?= BASE_URL ?>admin?page=orders" class="btn btn-sm btn-admin-primary">
                        Xem tất cả
                    </a>
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
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentOrders)): ?>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL ?>admin?page=order-detail&id=<?= $order['id'] ?? '' ?>" 
                                               class="order-code">#<?= $order['order_code'] ?? $order['id'] ?? 'N/A' ?></a>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <span class="name"><?= htmlspecialchars($order['customer_name'] ?? '') ?></span>
                                                <span class="phone"><?= $order['customer_phone'] ?? '' ?></span>
                                            </div>
                                        </td>
                                        <td><?= isset($order['items']) && is_array($order['items']) ? count($order['items']) : ($order['item_count'] ?? 0) ?> sản phẩm</td>
                                        <td class="fw-bold"><?= formatPrice($order['total'] ?? 0) ?></td>
                                        <td>
                                            <span class="status-badge <?= $order['status'] ?? '' ?>">
                                                <?= isset($order['status']) ? (ORDER_STATUSES[$order['status']] ?? $order['status']) : 'N/A' ?>
                                            </span>
                                        </td>
                                        <td><?= formatDate($order['created_at'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Top Products -->
        <div class="col-12">
            <div class="admin-card">
                <div class="card-header">
                    <h5>Sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <div class="top-products">
                        <?php foreach ($topProducts as $index => $product): 
                            $imgPath = $product['primary_image'] ?? 'assets/images/no-image.jpg';
                            $imageUrl = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                        ?>
                            <div class="product-item">
                                <span class="rank rank-<?= $index + 1 ?>"><?= $index + 1 ?></span>
                                <img src="<?= $imageUrl ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>">
                                <div class="product-info">
                                    <h6><?= htmlspecialchars($product['name']) ?></h6>
                                    <span class="sold"><?= $product['sold_count'] ?> đã bán</span>
                                </div>
                                <span class="price"><?= formatPrice($product['price']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Low Stock Products -->
        <div class="col-xl-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5><i class="fas fa-exclamation-triangle text-warning"></i> Sản phẩm sắp hết hàng</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Tồn kho</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): 
                                    $imgPath = $product['primary_image'] ?? 'assets/images/no-image.jpg';
                                    $imageUrl = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="product-mini">
                                                <img src="<?= $imageUrl ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>">
                                                <span><?= htmlspecialchars($product['name']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="stock-badge low"><?= $product['stock'] ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>admin?page=product-edit&id=<?= $product['id'] ?>" 
                                               class="btn-icon edit" title="Cập nhật">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="col-xl-6">
            <div class="admin-card">
                <div class="card-header">
                    <h5>Đánh giá mới</h5>
                    <a href="<?= BASE_URL ?>admin?page=reviews" class="btn btn-sm btn-admin-outline">
                        Xem tất cả
                    </a>
                </div>
                <div class="card-body">
                    <div class="recent-reviews">
                        <?php foreach ($recentReviews as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer">
                                        <img src="<?= BASE_URL ?>assets/images/default-avatar.svg" alt="Avatar">
                                        <div>
                                            <span class="name"><?= htmlspecialchars($review['user_name']) ?></span>
                                            <span class="date"><?= formatDate($review['created_at']) ?></span>
                                        </div>
                                    </div>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $review['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="review-content"><?= htmlspecialchars(substr($review['content'], 0, 100)) ?>...</p>
                                <div class="review-product">
                                    <i class="fas fa-box"></i>
                                    <?= htmlspecialchars($review['product_name']) ?>
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
/* Chart Period */
.chart-period {
    display: flex;
    gap: 5px;
}

.chart-period .btn.active {
    background: var(--admin-primary);
    border-color: var(--admin-primary);
    color: #fff;
}

/* Status Legend */
.status-legend {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
}

.legend-item .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.dot.pending { background: #f59e0b; }
.dot.confirmed { background: #3b82f6; }
.dot.shipping { background: #8b5cf6; }
.dot.delivered { background: #10b981; }
.dot.cancelled { background: #ef4444; }

/* Order Code */
.order-code {
    color: var(--admin-primary);
    font-weight: 600;
}

/* Customer Info */
.customer-info {
    display: flex;
    flex-direction: column;
}

.customer-info .name {
    font-weight: 500;
}

.customer-info .phone {
    font-size: 12px;
    color: #64748b;
}

/* Top Products */
.top-products {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.product-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: var(--admin-primary);
}

.product-item .rank {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    background: #e2e8f0;
    color: #64748b;
    flex-shrink: 0;
}

.rank.rank-1 { 
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
}
.rank.rank-2 { 
    background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(148, 163, 184, 0.3);
}
.rank.rank-3 { 
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
    color: #fff;
    box-shadow: 0 2px 8px rgba(251, 146, 60, 0.3);
}

.product-item img {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    background: #f8fafc;
    padding: 5px;
    flex-shrink: 0;
}

.product-item .product-info {
    flex: 1;
    min-width: 0;
}

.product-item h6 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: #1e293b;
    line-height: 1.5;
    display: block;
}

.product-item .sold {
    font-size: 12px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}

.product-item .sold::before {
    content: "📦";
    font-size: 11px;
}

.product-item .price {
    font-weight: 700;
    color: #dc2626;
    font-size: 15px;
    white-space: nowrap;
    flex-shrink: 0;
}

/* Product Mini */
.product-mini {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-mini img {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
}

/* Stock Badge */
.stock-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.stock-badge.low {
    background: #fee2e2;
    color: #dc2626;
}

/* Recent Reviews */
.recent-reviews {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-item {
    padding-bottom: 20px;
    border-bottom: 1px solid #e2e8f0;
}

.review-item:last-child {
    padding-bottom: 0;
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer {
    display: flex;
    align-items: center;
    gap: 12px;
}

.reviewer img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.reviewer .name {
    display: block;
    font-weight: 500;
    font-size: 14px;
}

.reviewer .date {
    font-size: 12px;
    color: #94a3b8;
}

.rating i {
    font-size: 12px;
}

.review-content {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 10px;
    line-height: 1.5;
}

.review-product {
    font-size: 12px;
    color: #94a3b8;
}

.review-product i {
    margin-right: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revenueLabels ?? ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']) ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?= json_encode($revenueData ?? [0, 0, 0, 0, 0, 0, 0]) ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                notation: 'compact'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
    
    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'],
            datasets: [{
                data: [
                    <?= $orderStats['pending'] ?? 0 ?>,
                    <?= $orderStats['confirmed'] ?? 0 ?>,
                    <?= $orderStats['shipping'] ?? 0 ?>,
                    <?= $orderStats['delivered'] ?? 0 ?>,
                    <?= $orderStats['cancelled'] ?? 0 ?>
                ],
                backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });
    
    // Chart period buttons
    document.querySelectorAll('.chart-period .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-period .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // TODO: Fetch new data based on period
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


