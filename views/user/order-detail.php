<?php 
$pageTitle = 'Chi tiet don hang #' . $order['id'] . ' - ' . SITE_NAME;
include __DIR__ . '/../layouts/header.php'; 
?>

<section class="order-detail-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chu</a></li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>don-hang">Don hang</a></li>
                        <li class="breadcrumb-item active">#<?= $order['id'] ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Order Info -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Don hang #<?= $order['id'] ?></h5>
                        <span class="badge bg-<?= getOrderStatusColor($order['status']) ?>">
                            <?= getOrderStatusText($order['status']) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Ngay dat:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                                <p class="mb-1"><strong>Phuong thuc thanh toan:</strong> <?= $order['payment_method'] === 'cod' ? 'Thanh toan khi nhan hang' : 'Chuyen khoan' ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nguoi nhan:</strong> <?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
                                <p class="mb-1"><strong>SDT:</strong> <?= htmlspecialchars($order['customer_phone'] ?? '') ?></p>
                            </div>
                        </div>
                        <p class="mb-0"><strong>Dia chi:</strong> <?= htmlspecialchars($order['shipping_address'] ?? '') ?></p>
                        <?php if (!empty($order['note'])): ?>
                        <p class="mb-0 mt-2"><strong>Ghi chu:</strong> <?= htmlspecialchars($order['note']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">San pham</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>San pham</th>
                                        <th class="text-center">So luong</th>
                                        <th class="text-end">Don gia</th>
                                        <th class="text-end">Thanh tien</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $items = is_string($order['items']) ? json_decode($order['items'], true) : $order['items'];
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
                                                     class="img-thumbnail me-2" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['product_name'] ?? $item['name'] ?? '') ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= formatPrice($item['price']) ?></td>
                                        <td class="text-end"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Tong cong</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tam tinh:</span>
                            <span><?= formatPrice($order['subtotal']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phi van chuyen:</span>
                            <span><?= formatPrice($order['shipping_fee'] ?? 0) ?></span>
                        </div>
                        <?php if (!empty($order['discount'])): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giam gia:</span>
                            <span>-<?= formatPrice($order['discount']) ?></span>
                        </div>
                        <?php endif; ?>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tong cong:</strong>
                            <strong class="text-primary"><?= formatPrice($order['total']) ?></strong>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <?php if ($order['status'] === 'pending'): ?>
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-danger w-100" onclick="cancelOrder('<?= $order['id'] ?>')">
                            <i class="fas fa-times"></i> Huy don hang
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="<?= BASE_URL ?>don-hang" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Quay lai danh sach
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function cancelOrder(orderId) {
    if (confirm('Ban co chac chan muon huy don hang nay?')) {
        fetch('<?= BASE_URL ?>api/orders.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=cancel&order_id=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Da huy don hang thanh cong!');
                location.reload();
            } else {
                alert(data.message || 'Co loi xay ra!');
            }
        });
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

