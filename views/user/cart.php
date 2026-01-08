<?php
$pageTitle = 'Giỏ hàng';
$subtotal = $cartTotal ?? 0;
include __DIR__ . '/../layouts/header.php';
?>

<!-- Cart Section -->
<section class="cart-section py-5">
    <div class="container">
        <h2 class="section-title mb-4">
            <i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn
        </h2>

        <?php if (empty($cartItems)): ?>
            <!-- Empty Cart -->
            <div class="empty-cart text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4>Giỏ hàng trống</h4>
                <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="<?= BASE_URL ?>products" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-items-container">
                        <!-- Select All -->
                        <div class="cart-header">
                            <label class="select-all-checkbox">
                                <input type="checkbox" id="selectAll" checked>
                                <span>Chọn tất cả (<?= count($cartItems) ?> sản phẩm)</span>
                            </label>
                            <button type="button" class="btn-delete-selected" id="deleteSelected">
                                <i class="fas fa-trash"></i> Xóa đã chọn
                            </button>
                        </div>

                        <!-- Cart Items List -->
                        <div class="cart-items" id="cartItems">
                            <?php foreach ($cartItems as $item): 
                                $imageUrl = !empty($item['image']) ? 
                                    ((strpos($item['image'], 'http') === 0) ? $item['image'] : BASE_URL . $item['image']) 
                                    : BASE_URL . 'assets/images/no-image.jpg';
                                $displayPrice = $item['sale_price'] ?? $item['price'];
                            ?>
                                <div class="cart-item" data-item-id="<?= $item['id'] ?>" data-product-id="<?= $item['product_id'] ?>">
                                    <div class="item-checkbox">
                                        <input type="checkbox" class="item-select" 
                                               data-item-id="<?= $item['id'] ?>" checked>
                                    </div>
                                    <div class="item-image">
                                        <a href="<?= BASE_URL ?>product/<?= $item['slug'] ?>">
                                            <img src="<?= $imageUrl ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>">
                                        </a>
                                    </div>
                                    <div class="item-details">
                                        <h5 class="item-name">
                                            <a href="<?= BASE_URL ?>product/<?= $item['slug'] ?>">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </a>
                                        </h5>
                                        <div class="item-brand"><?= htmlspecialchars($item['brand'] ?? '') ?></div>
                                    </div>
                                    <div class="item-price">
                                        <span class="current-price"><?= number_format($displayPrice, 0, ',', '.') ?> VNĐ</span>
                                        <?php if (!empty($item['sale_price']) && $item['sale_price'] < $item['price']): ?>
                                            <span class="original-price"><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-quantity">
                                        <button type="button" class="qty-btn minus" data-product-id="<?= $item['product_id'] ?>">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="qty-input" value="<?= $item['quantity'] ?>" 
                                               min="1" max="<?= $item['stock'] ?>" 
                                               data-product-id="<?= $item['product_id'] ?>">
                                        <button type="button" class="qty-btn plus" data-product-id="<?= $item['product_id'] ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="item-total">
                                        <span class="total-price" data-price="<?= $displayPrice ?>">
                                            <?= number_format($displayPrice * $item['quantity'], 0, ',', '.') ?> VNĐ
                                        </span>
                                    </div>
                                    <div class="item-actions">
                                        <button type="button" class="btn-wishlist" title="Thêm vào yêu thích">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <button type="button" class="btn-remove" data-product-id="<?= $item['product_id'] ?>" title="Xóa">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Continue Shopping -->
                    <div class="continue-shopping mt-4">
                        <a href="<?= BASE_URL ?>?page=products" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h5 class="summary-title">Tóm tắt đơn hàng</h5>
                        
                        <!-- Coupon -->
                        <div class="coupon-section">
                            <div class="input-group">
                                <input type="text" class="form-control" id="couponCode" 
                                       placeholder="Nhập mã giảm giá">
                                <button type="button" class="btn btn-coupon" id="applyCoupon">
                                    Áp dụng
                                </button>
                            </div>
                            <div class="coupon-applied" id="couponApplied" style="display: none;">
                                <span class="coupon-name"></span>
                                <button type="button" class="btn-remove-coupon">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="summary-details">
                            <div class="summary-row">
                                <span>Tạm tính</span>
                                <span id="subtotal"><?= formatPrice($subtotal ?? 0) ?></span>
                            </div>
                            <div class="summary-row discount-row" style="display: none;">
                                <span>Giảm giá</span>
                                <span id="discountAmount" class="text-danger">-0đ</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí vận chuyển</span>
                                <span id="shippingFee">
                                    <?php if (($subtotal ?? 0) >= 500000): ?>
                                        <span class="free-shipping">Miễn phí</span>
                                    <?php else: ?>
                                        <?= formatPrice(30000) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="summary-total">
                                <span>Tổng cộng</span>
                                <span id="totalAmount" class="total-value">
                                    <?php 
                                    $shippingFee = ($subtotal ?? 0) >= 500000 ? 0 : 30000;
                                    echo formatPrice(($subtotal ?? 0) + $shippingFee);
                                    ?>
                                </span>
                            </div>
                        </div>

                        <div class="shipping-note">
                            <i class="fas fa-truck"></i>
                            <span>Miễn phí vận chuyển cho đơn hàng từ 500.000đ</span>
                        </div>

                        <a href="<?= BASE_URL ?>checkout" class="btn btn-primary btn-lg w-100 py-3 mt-3">
                            <i class="fas fa-lock me-2"></i> Tiến hành thanh toán
                        </a>

                        <div class="secure-badge">
                            <i class="fas fa-shield-alt"></i>
                            <span>Thanh toán an toàn & bảo mật</span>
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div class="cart-benefits mt-4">
                        <div class="benefit-item">
                            <i class="fas fa-undo"></i>
                            <div>
                                <strong>Đổi trả miễn phí</strong>
                                <span>Trong vòng 7 ngày</span>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <strong>Bảo hành chính hãng</strong>
                                <span>12-36 tháng</span>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-headset"></i>
                            <div>
                                <strong>Hỗ trợ 24/7</strong>
                                <span>Hotline: 1900 1234</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Section Title */
.section-title {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary-color);
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.empty-cart img {
    max-width: 250px;
    margin-bottom: 30px;
    opacity: 0.8;
}

.empty-cart h4 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-cart p {
    color: #64748b;
    margin-bottom: 25px;
}

/* Cart Items Container */
.cart-items-container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.select-all-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
}

.select-all-checkbox input {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.btn-delete-selected {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Cart Item */
.cart-item {
    display: grid;
    grid-template-columns: 40px 100px 1fr 150px 150px 150px 80px;
    align-items: center;
    gap: 15px;
    padding: 20px 25px;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.3s;
}

.cart-item:hover {
    background: #f8fafc;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-checkbox input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.item-image {
    width: 100px;
    height: 100px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.item-name {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 5px;
    line-height: 1.4;
}

.item-name a {
    color: #1e293b;
    transition: color 0.3s;
}

.item-name a:hover {
    color: var(--primary-color);
}

.item-brand {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 5px;
}

.item-discount {
    display: inline-block;
    background: #fee2e2;
    color: #dc2626;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
}

.item-price {
    text-align: center;
}

.item-price .current-price {
    font-size: 16px;
    font-weight: 600;
    color: #dc2626;
    display: block;
}

.item-price .original-price {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
}

.item-quantity {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.qty-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #e2e8f0;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.qty-btn:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
}

.qty-input {
    width: 50px;
    height: 32px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
}

.item-total {
    text-align: center;
}

.total-price {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
}

.item-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-wishlist,
.btn-remove {
    width: 36px;
    height: 36px;
    border: 1px solid #e2e8f0;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-wishlist:hover {
    color: #ef4444;
    border-color: #ef4444;
}

.btn-remove:hover {
    background: #fee2e2;
    border-color: #ef4444;
    color: #ef4444;
}

/* Cart Summary */
.cart-summary {
    background: #fff;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
}

.coupon-section {
    margin-bottom: 20px;
}

.coupon-section .input-group {
    display: flex;
    gap: 10px;
}

.coupon-section .form-control {
    flex: 1;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    padding: 12px 15px;
}

.btn-coupon {
    background: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0 20px;
    font-weight: 500;
}

.coupon-applied {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #d1fae5;
    padding: 10px 15px;
    border-radius: 10px;
    margin-top: 10px;
}

.coupon-name {
    font-weight: 500;
    color: #059669;
}

.btn-remove-coupon {
    background: none;
    border: none;
    color: #059669;
    cursor: pointer;
}

.summary-details {
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    font-size: 15px;
    border-bottom: 1px solid #f1f5f9;
}

.free-shipping {
    color: #10b981;
    font-weight: 500;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 700;
}

.total-value {
    color: #dc2626;
}

.shipping-note {
    background: #fef3c7;
    padding: 12px 15px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #92400e;
    margin-bottom: 20px;
}

.btn-checkout {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--primary-color) 0%, #1557b0 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(26, 115, 232, 0.4);
    color: #fff;
}

.secure-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 15px;
    color: #64748b;
    font-size: 13px;
}

.secure-badge i {
    color: #10b981;
}

/* Cart Benefits */
.cart-benefits {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f1f5f9;
}

.benefit-item:last-child {
    border-bottom: none;
}

.benefit-item i {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.benefit-item strong {
    display: block;
    font-size: 14px;
    margin-bottom: 2px;
}

.benefit-item span {
    font-size: 12px;
    color: #64748b;
}

/* Responsive */
@media (max-width: 1199px) {
    .cart-item {
        grid-template-columns: 40px 80px 1fr 120px 120px 100px 60px;
    }
}

@media (max-width: 991px) {
    .cart-item {
        grid-template-columns: 30px 70px 1fr auto;
        gap: 10px;
    }
    
    .item-price,
    .item-total,
    .item-actions {
        display: none;
    }
    
    .cart-summary {
        position: static;
        margin-top: 30px;
    }
}

@media (max-width: 576px) {
    .cart-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .cart-item {
        grid-template-columns: 30px 60px 1fr;
    }
    
    .item-quantity {
        grid-column: 2 / 4;
        justify-content: flex-start;
        margin-top: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.item-select').forEach(cb => {
            cb.checked = this.checked;
        });
        updateCartSummary();
    });
    
    // Individual select
    document.querySelectorAll('.item-select').forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = document.querySelectorAll('.item-select:checked').length === 
                               document.querySelectorAll('.item-select').length;
            document.getElementById('selectAll').checked = allChecked;
            updateCartSummary();
        });
    });
    
    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
            let value = parseInt(input.value) || 1;
            const max = parseInt(input.max) || 99;
            
            if (this.classList.contains('minus') && value > 1) {
                value--;
            } else if (this.classList.contains('plus') && value < max) {
                value++;
            }
            
            input.value = value;
            updateItemQuantity(productId, value);
        });
    });
    
    // Quantity input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            let value = parseInt(this.value) || 1;
            const max = parseInt(this.max) || 99;
            
            if (value < 1) value = 1;
            if (value > max) value = max;
            
            this.value = value;
            updateItemQuantity(productId, value);
        });
    });
    
    // Remove item
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: 'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeCartItem(productId);
                }
            });
        });
    });
    
    // Delete selected
    document.getElementById('deleteSelected')?.addEventListener('click', function() {
        const selected = document.querySelectorAll('.item-select:checked');
        if (selected.length === 0) {
            Swal.fire('Thông báo', 'Vui lòng chọn sản phẩm cần xóa', 'info');
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Bạn có chắc muốn xóa ${selected.length} sản phẩm đã chọn?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                const itemIds = Array.from(selected).map(cb => cb.dataset.itemId);
                removeMultipleItems(itemIds);
            }
        });
    });
    
    // Apply coupon
    document.getElementById('applyCoupon')?.addEventListener('click', function() {
        const code = document.getElementById('couponCode').value.trim();
        if (!code) {
            Swal.fire('Lỗi', 'Vui lòng nhập mã giảm giá', 'error');
            return;
        }
        applyCoupon(code);
    });
});

function updateItemQuantity(productId, quantity) {
    fetch('<?= BASE_URL ?>api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'update',
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item total
            const item = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            const priceEl = item.querySelector('.total-price');
            const price = parseFloat(priceEl.dataset.price);
            priceEl.textContent = formatPrice(price * quantity);
            
            updateCartSummary();
            updateCartCount(data.cart_count);
        }
    });
}

function removeCartItem(productId) {
    console.log('Removing product:', productId);
    fetch('<?= BASE_URL ?>api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'remove',
            product_id: productId
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const item = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            if (item) {
                item.remove();
            }
            
            updateCartSummary();
            updateCartCount(data.cart_count);
            
            Swal.fire({
                icon: 'success',
                title: 'Đã xóa!',
                text: 'Sản phẩm đã được xóa khỏi giỏ hàng',
                timer: 1500,
                showConfirmButton: false
            });
            
            // Check if cart is empty
            if (document.querySelectorAll('.cart-item').length === 0) {
                location.reload();
            }
        } else {
            Swal.fire('Lỗi', data.message || 'Không thể xóa sản phẩm', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
    });
}

function removeMultipleItems(itemIds) {
    console.log('Removing items:', itemIds);
    fetch('<?= BASE_URL ?>api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'remove_multiple',
            item_ids: itemIds
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            itemIds.forEach(id => {
                const item = document.querySelector(`.cart-item[data-item-id="${id}"]`);
                if (item) item.remove();
            });
            
            updateCartSummary();
            updateCartCount(data.cart_count);
            
            Swal.fire({
                icon: 'success',
                title: 'Đã xóa!',
                text: 'Đã xóa các sản phẩm đã chọn',
                timer: 1500,
                showConfirmButton: false
            });
            
            if (document.querySelectorAll('.cart-item').length === 0) {
                location.reload();
            }
        } else {
            Swal.fire('Lỗi', data.message || 'Không thể xóa sản phẩm', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
    });
}

function applyCoupon(code) {
    fetch('<?= BASE_URL ?>api/cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'apply_coupon',
            code: code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('couponCode').value = '';
            document.getElementById('couponApplied').style.display = 'flex';
            document.querySelector('.coupon-name').textContent = code + ' - ' + data.discount_text;
            document.querySelector('.discount-row').style.display = 'flex';
            document.getElementById('discountAmount').textContent = '-' + formatPrice(data.discount_amount);
            updateCartSummary();
            
            Swal.fire('Thành công', 'Đã áp dụng mã giảm giá', 'success');
        } else {
            Swal.fire('Lỗi', data.message || 'Mã giảm giá không hợp lệ', 'error');
        }
    });
}

function updateCartSummary() {
    let subtotal = 0;
    
    document.querySelectorAll('.cart-item').forEach(item => {
        const checkbox = item.querySelector('.item-select');
        if (checkbox.checked) {
            const priceEl = item.querySelector('.total-price');
            const qty = parseInt(item.querySelector('.qty-input').value);
            const price = parseFloat(priceEl.dataset.price);
            subtotal += price * qty;
        }
    });
    
    const shippingFee = subtotal >= 500000 ? 0 : 30000;
    const total = subtotal + shippingFee;
    
    document.getElementById('subtotal').textContent = formatPrice(subtotal);
    document.getElementById('shippingFee').innerHTML = shippingFee === 0 
        ? '<span class="free-shipping">Miễn phí</span>' 
        : formatPrice(shippingFee);
    document.getElementById('totalAmount').textContent = formatPrice(total);
}

function updateCartCount(count) {
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.display = count > 0 ? 'flex' : 'none';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


