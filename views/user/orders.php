<?php
$pageTitle = 'Đơn hàng của tôi';

// Define order statuses
$ORDER_STATUSES = [
    'pending' => 'Chờ xác nhận',
    'confirmed' => 'Đã xác nhận', 
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao',
    'delivered' => 'Đã giao',
    'cancelled' => 'Đã hủy'
];

include __DIR__ . '/../layouts/header.php';
?>

<!-- Orders Section -->
<section class="orders-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Orders Content -->
            <div class="col-lg-9">
                <div class="orders-container">
                    <div class="section-header">
                        <h4><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</h4>
                    </div>

                    <!-- Order Tabs -->
                    <div class="order-tabs">
                        <button class="tab-btn active" data-status="all">Tất cả</button>
                        <button class="tab-btn" data-status="pending">Chờ xác nhận</button>
                        <button class="tab-btn" data-status="confirmed">Đã xác nhận</button>
                        <button class="tab-btn" data-status="shipping">Đang giao</button>
                        <button class="tab-btn" data-status="delivered">Đã giao</button>
                        <button class="tab-btn" data-status="cancelled">Đã hủy</button>
                    </div>

                    <!-- Orders List -->
                    <div class="orders-list" id="ordersList">
                        <?php if (empty($orders)): ?>
                            <div class="empty-orders text-center py-5">
                                <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                                <h5>Chưa có đơn hàng nào</h5>
                                <p class="text-muted">Hãy mua sắm và quay lại đây nhé!</p>
                                <a href="<?= BASE_URL ?>products" class="btn btn-primary">
                                    Mua sắm ngay
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card" data-status="<?= $order['status'] ?>">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <span class="order-code">Đơn hàng #<?= $order['order_number'] ?? $order['id'] ?></span>
                                            <span class="order-date">
                                                <?= formatDate($order['created_at']) ?>
                                            </span>
                                        </div>
                                        <span class="order-status status-<?= $order['status'] ?>">
                                            <?= $ORDER_STATUSES[$order['status']] ?? $order['status'] ?>
                                        </span>
                                    </div>

                                    <div class="order-items">
                                        <?php foreach (array_slice($order['items'] ?? [], 0, 2) as $item): 
                                            $imgPath = $item['product_image'] ?? $item['image'] ?? 'assets/images/no-image.jpg';
                                            $imageUrl = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                                            $itemName = $item['product_name'] ?? $item['name'] ?? '';
                                        ?>
                                            <div class="order-item">
                                                <div class="item-image">
                                                    <img src="<?= $imageUrl ?>" 
                                                         alt="<?= htmlspecialchars($itemName) ?>">
                                                </div>
                                                <div class="item-info">
                                                    <h6><?= htmlspecialchars($itemName) ?></h6>
                                                    <span class="item-qty">x<?= $item['quantity'] ?></span>
                                                </div>
                                                <div class="item-price">
                                                    <?= formatPrice($item['price'] * $item['quantity']) ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (count($order['items'] ?? []) > 2): ?>
                                            <div class="more-items">
                                                +<?= count($order['items']) - 2 ?> sản phẩm khác
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="order-footer">
                                        <div class="order-total">
                                            <span>Tổng tiền:</span>
                                            <strong><?= formatPrice($order['total']) ?></strong>
                                        </div>
                                        <div class="order-actions">
                                            <?php if ($order['status'] === 'pending'): ?>
                                                <button class="btn btn-outline-danger btn-cancel" 
                                                        data-order-id="<?= $order['id'] ?>">
                                                    Hủy đơn
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Debug: Show status -->
                                            <!-- Status: <?= $order['status'] ?> -->
                                            
                                            <?php if ($order['status'] === 'delivered' || $order['status'] === 'completed'): ?>
                                                <button class="btn btn-outline-primary btn-review" 
                                                        data-order-id="<?= $order['id'] ?>">
                                                    Đánh giá
                                                </button>
                                                <button class="btn btn-primary btn-reorder" 
                                                        data-order-id="<?= $order['id'] ?>">
                                                    Mua lại
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?= BASE_URL ?>don-hang/<?= $order['id'] ?>" 
                                               class="btn btn-outline-primary">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav class="orders-pagination mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=orders&p=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đánh giá sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" id="review_order_id" name="order_id">
                    <div id="reviewProducts"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="submitReviews()">Gửi đánh giá</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Section Header */
.orders-container {
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

/* Order Tabs */
.order-tabs {
    display: flex;
    overflow-x: auto;
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
}

.tab-btn {
    padding: 15px 25px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.3s;
}

.tab-btn:hover,
.tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

/* Review Modal */
.review-product-item {
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input {
    display: none;
}

.rating-input label {
    font-size: 32px;
    color: #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
    margin: 0;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input:checked ~ label {
    color: #fbbf24;
}

/* Orders List */
.orders-list {
    padding: 20px;
}

/* Empty Orders */
.empty-orders {
    text-align: center;
    padding: 60px 20px;
}

.empty-orders img {
    max-width: 200px;
    margin-bottom: 25px;
    opacity: 0.7;
}

.empty-orders h5 {
    font-size: 20px;
    margin-bottom: 10px;
}

.empty-orders p {
    color: #64748b;
    margin-bottom: 20px;
}

/* Order Card */
.order-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s;
}

.order-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.order-code {
    font-weight: 600;
    color: #1e293b;
}

.order-date {
    font-size: 13px;
    color: #64748b;
    margin-left: 15px;
}

.order-status {
    padding: 6px 15px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 500;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-confirmed {
    background: #dbeafe;
    color: #2563eb;
}

.status-shipping {
    background: #e0e7ff;
    color: #4f46e5;
}

.status-delivered {
    background: #d1fae5;
    color: #059669;
}

.status-cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.order-items {
    padding: 15px 20px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item .item-image {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    background: #f8fafc;
    overflow: hidden;
}

.order-item .item-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.order-item .item-info {
    flex: 1;
}

.order-item .item-info h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 5px;
    line-height: 1.4;
}

.item-qty {
    font-size: 13px;
    color: #64748b;
}

.order-item .item-price {
    font-weight: 600;
    color: #dc2626;
}

.more-items {
    text-align: center;
    padding: 10px;
    font-size: 14px;
    color: #64748b;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.order-total span {
    color: #64748b;
    margin-right: 10px;
}

.order-total strong {
    font-size: 18px;
    color: #dc2626;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.order-actions .btn {
    padding: 8px 16px;
    font-size: 13px;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 991px) {
    .order-footer {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}

@media (max-width: 576px) {
    .order-tabs {
        padding: 0 10px;
    }
    
    .tab-btn {
        padding: 12px 15px;
        font-size: 13px;
    }
    
    .order-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .order-actions {
        flex-wrap: wrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab filter
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const status = this.dataset.status;
            
            document.querySelectorAll('.order-card').forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Cancel order
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            
            Swal.fire({
                title: 'Xác nhận hủy đơn?',
                text: 'Bạn có chắc muốn hủy đơn hàng này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Không',
                confirmButtonColor: '#dc2626'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelOrder(orderId);
                }
            });
        });
    });
    
    // Reorder
    document.querySelectorAll('.btn-reorder').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            reorder(orderId);
        });
    });
    
    // Review
    document.querySelectorAll('.btn-review').forEach(btn => {
        console.log('Found review button:', btn);
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Review button clicked!');
            console.log('Button dataset:', this.dataset);
            const orderId = this.getAttribute('data-order-id');
            console.log('Order ID:', orderId);
            
            if (!orderId) {
                alert('Lỗi: Không tìm thấy ID đơn hàng');
                return;
            }
            
            openReviewModal(orderId);
        });
    });
});

function cancelOrder(orderId) {
    fetch('<?= BASE_URL ?>api/orders.php', {
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
            Swal.fire('Thành công', 'Đã hủy đơn hàng', 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Lỗi', data.message || 'Không thể hủy đơn hàng', 'error');
        }
    });
}

function reorder(orderId) {
    fetch('<?= BASE_URL ?>api/orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'reorder',
            order_id: orderId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Thành công', 'Đã thêm sản phẩm vào giỏ hàng', 'success')
            .then(() => {
                window.location.href = '<?= BASE_URL ?>?page=cart';
            });
        } else {
            Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
        }
    });
}

function openReviewModal(orderId) {
    console.log('openReviewModal called with orderId:', orderId);
    fetch('<?= BASE_URL ?>api/orders.php?action=detail&id=' + orderId)
    .then(response => {
        console.log('Response received:', response);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        if (data.success) {
            document.getElementById('review_order_id').value = orderId;
            const items = typeof data.order.items === 'string' ? JSON.parse(data.order.items) : data.order.items;
            let html = '';
            
            items.forEach((item, index) => {
                html += `
                    <div class="review-product-item mb-4 pb-4 border-bottom">
                        <div class="d-flex gap-3 mb-3">
                            <img src="${item.product_image || item.image || '<?= BASE_URL ?>assets/images/no-image.jpg'}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div>
                                <h6 class="mb-1">${item.product_name || item.name}</h6>
                                <p class="text-muted mb-0">Số lượng: ${item.quantity}</p>
                            </div>
                        </div>
                        <input type="hidden" name="products[${index}][product_id]" value="${item.product_id}">
                        <div class="mb-3">
                            <label class="form-label">Đánh giá của bạn</label>
                            <div class="rating-input">
                                ${[5,4,3,2,1].map(star => `
                                    <input type="radio" id="star${star}_${index}" name="products[${index}][rating]" value="${star}" required>
                                    <label for="star${star}_${index}">★</label>
                                `).join('')}
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nhận xét</label>
                            <textarea class="form-control" name="products[${index}][comment]" rows="3" 
                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." required></textarea>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById('reviewProducts').innerHTML = html;
            
            // Show modal
            console.log('Showing modal...');
            const modalElement = document.getElementById('reviewModal');
            console.log('Modal element:', modalElement);
            
            if (typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal shown via Bootstrap');
            } else {
                console.error('Bootstrap is not loaded!');
                alert('Lỗi: Bootstrap chưa được tải. Vui lòng refresh trang.');
            }
        } else {
            console.error('API error:', data.message);
            alert('Lỗi: ' + (data.message || 'Không thể tải thông tin đơn hàng'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Lỗi kết nối: ' + error.message);
    });
}

function submitReviews() {
    console.log('submitReviews called');
    
    const form = document.getElementById('reviewForm');
    if (!form) {
        console.error('Form not found');
        Swal.fire('Lỗi', 'Không tìm thấy form đánh giá', 'error');
        return;
    }
    
    const formData = new FormData(form);
    const orderId = formData.get('order_id');
    
    console.log('Order ID:', orderId);
    
    // Parse form data
    const products = {};
    for (let [key, value] of formData.entries()) {
        console.log('Form entry:', key, '=', value);
        const match = key.match(/products\[(\d+)\]\[(\w+)\]/);
        if (match) {
            const index = match[1];
            const field = match[2];
            if (!products[index]) products[index] = {};
            products[index][field] = value;
        }
    }
    
    const reviews = Object.values(products);
    console.log('Reviews to submit:', reviews);
    
    // Validate
    if (reviews.length === 0) {
        Swal.fire('Lỗi', 'Không có sản phẩm để đánh giá', 'error');
        return;
    }
    
    // Check if all products have rating
    let hasError = false;
    reviews.forEach((review, idx) => {
        if (!review.rating) {
            hasError = true;
        }
    });
    
    if (hasError) {
        Swal.fire('Lỗi', 'Vui lòng chọn số sao cho tất cả sản phẩm', 'error');
        return;
    }
    
    const data = {
        action: 'add_review',
        order_id: orderId,
        reviews: reviews
    };
    
    console.log('Sending data:', JSON.stringify(data));
    
    // Show loading
    Swal.fire({
        title: 'Đang gửi...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('<?= BASE_URL ?>api/reviews.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(result => {
        console.log('Result:', result);
        if (result.success) {
            Swal.fire('Thành công', 'Cảm ơn bạn đã đánh giá!', 'success')
            .then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                if (modal) modal.hide();
                location.reload();
            });
        } else {
            Swal.fire('Lỗi', result.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Lỗi', 'Lỗi kết nối: ' + error.message, 'error');
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


