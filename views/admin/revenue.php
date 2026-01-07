<?php
$pageTitle = 'Báo cáo doanh thu';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Báo cáo doanh thu</h4>
            <p>Thống kê doanh thu và phân tích kinh doanh</p>
        </div>
        <div class="header-right">
            <div class="date-range-picker">
                <input type="date" class="form-control" id="startDate" value="<?= date('Y-m-01') ?>">
                <span>đến</span>
                <input type="date" class="form-control" id="endDate" value="<?= date('Y-m-d') ?>">
                <button class="btn btn-admin-primary" id="filterBtn">
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>
        </div>
    </div>

    <!-- Revenue Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-info">
                    <h3><?= formatPrice($stats['total_revenue'] ?? 0) ?></h3>
                    <p>Tổng doanh thu</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-info">
                    <h3><?= number_format($stats['total_orders'] ?? 0) ?></h3>
                    <p>Tổng đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stats-info">
                    <h3><?= formatPrice($stats['avg_order_value'] ?? 0) ?></h3>
                    <p>Giá trị TB/đơn</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-info">
                    <h3><?= number_format($stats['delivered_orders'] ?? 0) ?></h3>
                    <p>Đơn hoàn thành</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="card-header">
                    <h6><i class="fas fa-chart-area me-2"></i>Biểu đồ doanh thu</h6>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-outline-secondary active" data-period="7">7 ngày</button>
                        <button class="btn btn-sm btn-outline-secondary" data-period="30">30 ngày</button>
                        <button class="btn btn-sm btn-outline-secondary" data-period="90">3 tháng</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="card-header">
                    <h6><i class="fas fa-pie-chart me-2"></i>Trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Top Products -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h6><i class="fas fa-trophy me-2"></i>Sản phẩm bán chạy</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Đã bán</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topProducts)): ?>
                                    <?php foreach ($topProducts as $index => $product): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="product-cell-mini">
                                                <img src="<?= BASE_URL . ($product['primary_image'] ?? 'assets/images/no-image.jpg') ?>" alt="">
                                                <span><?= htmlspecialchars($product['name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= number_format($product['sold_count'] ?? 0) ?></td>
                                        <td><?= formatPrice(($product['price'] ?? 0) * ($product['sold_count'] ?? 0)) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="card-header">
                    <h6><i class="fas fa-users me-2"></i>Khách hàng tiềm năng</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Đơn hàng</th>
                                    <th>Tổng chi tiêu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topCustomers)): ?>
                                    <?php foreach ($topCustomers as $index => $customer): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <div class="user-cell-mini">
                                                <img src="<?= BASE_URL . ($customer['avatar'] ?? 'assets/images/default-avatar.svg') ?>" alt="">
                                                <div>
                                                    <span><?= htmlspecialchars($customer['name'] ?? '') ?></span>
                                                    <small><?= htmlspecialchars($customer['email'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($customer['order_count'] ?? 0) ?></td>
                                        <td><?= formatPrice($customer['total_spent'] ?? 0) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.date-range-picker {
    display: flex;
    align-items: center;
    gap: 10px;
}
.date-range-picker input {
    width: 150px;
}
.chart-actions .btn.active {
    background-color: var(--admin-primary);
    color: white;
}
.product-cell-mini {
    display: flex;
    align-items: center;
    gap: 10px;
}
.product-cell-mini img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 6px;
}
.user-cell-mini {
    display: flex;
    align-items: center;
    gap: 10px;
}
.user-cell-mini img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
}
.user-cell-mini div {
    display: flex;
    flex-direction: column;
}
.user-cell-mini small {
    color: #6c757d;
    font-size: 0.75rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($revenueData ?? [], 'date')) ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?= json_encode(array_column($revenueData ?? [], 'revenue')) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            }
        }
    });

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const orderStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [
                    <?= $orderStats['pending'] ?? 0 ?>,
                    <?= $orderStats['shipping'] ?? 0 ?>,
                    <?= $orderStats['delivered'] ?? 0 ?>,
                    <?= $orderStats['cancelled'] ?? 0 ?>
                ],
                backgroundColor: ['#f59e0b', '#8b5cf6', '#10b981', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Period buttons
    document.querySelectorAll('.chart-actions .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-actions .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            // TODO: Load data for period
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
