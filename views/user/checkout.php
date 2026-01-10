<?php
$pageTitle = 'Thanh toán';
$subtotal = $cartTotal ?? 0;
include __DIR__ . '/../layouts/header.php';
?>

<!-- Checkout Section -->
<section class="checkout-section py-5">
    <div class="container">
        <form id="checkoutForm" method="POST">
            <div class="row">
                <!-- Checkout Form -->
                <div class="col-lg-7">
                    <!-- Shipping Info -->
                    <div class="checkout-card">
                        <div class="card-header">
                            <h5><i class="fas fa-map-marker-alt"></i> Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="fullname" 
                                           value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <select class="form-select" name="province" id="province" required>
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <select class="form-select" name="district" id="district" required>
                                        <option value="">Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                    <select class="form-select" name="ward" id="ward" required>
                                        <option value="">Chọn Phường/Xã</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" 
                                           placeholder="Số nhà, tên đường..." required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Ghi chú đơn hàng</label>
                                    <textarea class="form-control" name="note" rows="3" 
                                              placeholder="Ghi chú về đơn hàng, ví dụ: thời gian giao hàng hoặc địa điểm giao hàng chi tiết..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card mt-4">
                        <div class="card-header">
                            <h5><i class="fas fa-credit-card"></i> Phương thức thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <label class="payment-method active">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <div class="method-content">
                                        <div class="payment-icon cod-icon">
                                            <i class="fas fa-truck"></i>
                                        </div>
                                        <div>
                                            <strong>Thanh toán khi nhận hàng (COD)</strong>
                                            <span>Thanh toán bằng tiền mặt khi nhận hàng</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="bank_transfer">
                                    <div class="method-content">
                                        <div class="payment-icon bank-icon">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div>
                                            <strong>Chuyển khoản ngân hàng</strong>
                                            <span>Chuyển khoản qua tài khoản ngân hàng</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="momo">
                                    <div class="method-content">
                                        <div class="payment-icon momo-icon">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div>
                                            <strong>Ví MoMo</strong>
                                            <span>Thanh toán qua ví điện tử MoMo</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="payment-method">
                                    <input type="radio" name="payment_method" value="vnpay">
                                    <div class="method-content">
                                        <div class="payment-icon vnpay-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div>
                                            <strong>VNPay</strong>
                                            <span>Thanh toán qua cổng VNPay (ATM/Visa/Master)</span>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Bank Transfer Info -->
                            <div class="bank-info" id="bankInfo" style="display: none;">
                                <h6>Thông tin chuyển khoản:</h6>
                                <ul>
                                    <li><strong>Ngân hàng:</strong> MBBank</li>
                                    <li><strong>Số tài khoản:</strong> 82184904112004</li>
                                    <li><strong>Chủ tài khoản:</strong>LE KHANH DUY</li>
                                    <li><strong>Nội dung:</strong> [Mã đơn hàng] - [Số điện thoại]</li>
                                </ul>
                                <p class="note">* Đơn hàng sẽ được xử lý sau khi nhận được thanh toán</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5">
                    <div class="order-summary">
                        <div class="summary-header">
                            <h5><i class="fas fa-shopping-bag"></i> Đơn hàng của bạn</h5>
                            <a href="<?= BASE_URL ?>cart" class="edit-cart">Sửa</a>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="order-items">
                            <?php foreach ($cartItems as $item): 
                                // Get product image - data is flat from Cart model
                                $imgPath = $item['image'] ?? 'assets/images/no-image.jpg';
                                $imageUrl = (strpos($imgPath, 'http') === 0) ? $imgPath : BASE_URL . $imgPath;
                            ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="<?= $imageUrl ?>" 
                                             alt="<?= htmlspecialchars($item['name'] ?? '') ?>">
                                        <span class="item-qty"><?= $item['quantity'] ?></span>
                                    </div>
                                    <div class="item-info">
                                        <h6><?= htmlspecialchars($item['name'] ?? '') ?></h6>
                                        <span class="item-variant"><?= htmlspecialchars($item['brand'] ?? '') ?></span>
                                    </div>
                                    <div class="item-price">
                                        <?php 
                                        $displayPrice = $item['sale_price'] ?? $item['price'] ?? 0;
                                        echo formatPrice($displayPrice * $item['quantity']);
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Coupon -->
                        <div class="coupon-section">
                            <div class="input-group">
                                <input type="text" class="form-control" id="couponCode" 
                                       placeholder="Nhập mã giảm giá">
                                <button type="button" class="btn btn-coupon" id="applyCoupon">
                                    Áp dụng
                                </button>
                            </div>
                        </div>

                        <!-- Summary Details -->
                        <div class="summary-details">
                            <div class="summary-row">
                                <span>Tạm tính</span>
                                <span><?= formatPrice($subtotal) ?></span>
                            </div>
                            <div class="summary-row discount-row" style="display: none;">
                                <span>Giảm giá</span>
                                <span id="discountAmount" class="text-danger">-0đ</span>
                            </div>
                            <div class="summary-row">
                                <span>Phí vận chuyển</span>
                                <span id="shippingFee">
                                    <?php if ($subtotal >= 500000): ?>
                                        <span class="free-shipping">Miễn phí</span>
                                    <?php else: ?>
                                        <?= formatPrice(30000) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <div class="summary-total">
                            <span>Tổng cộng</span>
                            <span class="total-value">
                                <?php 
                                $shippingFee = $subtotal >= 500000 ? 0 : 30000;
                                $total = $subtotal + $shippingFee;
                                echo formatPrice($total);
                                ?>
                            </span>
                        </div>

                        <!-- Place Order Button -->
                        <div class="checkout-action" style="padding: 20px 25px;">
                            <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                            <input type="hidden" name="shipping_fee" value="<?= $shippingFee ?>">
                            <input type="hidden" name="total" value="<?= $total ?>">
                            <input type="hidden" name="discount" value="0">

                            <button type="submit" class="btn btn-place-order" style="width: 100%; padding: 16px 24px; background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); color: #fff; border: none; border-radius: 12px; font-size: 18px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer;">
                                <i class="fas fa-lock"></i> Đặt hàng
                            </button>
                        </div>

                        <div class="order-note">
                            <p>Bằng việc đặt hàng, bạn đồng ý với 
                                <a href="#">Điều khoản sử dụng</a> và 
                                <a href="#">Chính sách bảo mật</a> của TechShop
                            </p>
                        </div>

                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="badge-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Thanh toán an toàn</span>
                            </div>
                            <div class="badge-item">
                                <i class="fas fa-truck"></i>
                                <span>Giao hàng toàn quốc</span>
                            </div>
                            <div class="badge-item">
                                <i class="fas fa-undo"></i>
                                <span>Đổi trả 7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
/* Checkout Card */
.checkout-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

.checkout-card .card-header {
    background: #f8fafc;
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.checkout-card .card-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.checkout-card .card-header h5 i {
    color: var(--primary-color);
}

.checkout-card .card-body {
    padding: 25px;
}

.form-label {
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control,
.form-select {
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.payment-method {
    display: block;
    cursor: pointer;
}

.payment-method input {
    display: none;
}

.method-content {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s;
}

.payment-method:hover .method-content,
.payment-method.active .method-content {
    border-color: var(--primary-color);
    background: #f0f9ff;
}

.payment-method input:checked + .method-content {
    border-color: var(--primary-color);
    background: #f0f9ff;
}

.method-content img {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

.payment-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.payment-icon.cod-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
}

.payment-icon.bank-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
}

.payment-icon.momo-icon {
    background: linear-gradient(135deg, #d63384 0%, #a61e4d 100%);
    color: #fff;
}

.payment-icon.vnpay-icon {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: #fff;
}

.method-content strong {
    display: block;
    font-size: 15px;
    margin-bottom: 3px;
}

.method-content span {
    font-size: 13px;
    color: #64748b;
}

.bank-info {
    background: #fef3c7;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
}

.bank-info h6 {
    margin-bottom: 15px;
    font-weight: 600;
}

.bank-info ul {
    list-style: none;
    padding: 0;
    margin: 0 0 15px;
}

.bank-info li {
    padding: 8px 0;
    border-bottom: 1px dashed #d97706;
    font-size: 14px;
}

.bank-info li:last-child {
    border-bottom: none;
}

.bank-info .note {
    font-size: 13px;
    color: #92400e;
    margin: 0;
}

/* Order Summary */
.order-summary {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
    overflow: hidden;
}

.summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.summary-header h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-header h5 i {
    color: var(--primary-color);
}

.edit-cart {
    color: var(--primary-color);
    font-size: 14px;
    font-weight: 500;
}

.order-items {
    padding: 20px 25px;
    max-height: 300px;
    overflow-y: auto;
    border-bottom: 1px solid #e2e8f0;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item .item-image {
    position: relative;
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

.item-qty {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 22px;
    height: 22px;
    background: var(--primary-color);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-item .item-info {
    flex: 1;
}

.order-item .item-info h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 3px;
    line-height: 1.4;
}

.item-variant {
    font-size: 12px;
    color: #64748b;
}

.order-item .item-price {
    font-weight: 600;
    font-size: 15px;
    color: #1e293b;
}

.order-summary .coupon-section {
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.coupon-section .input-group {
    display: flex;
    gap: 10px;
}

.coupon-section .form-control {
    flex: 1;
}

.btn-coupon {
    background: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0 20px;
    font-weight: 500;
}

.summary-details {
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    font-size: 14px;
}

.free-shipping {
    color: #10b981;
    font-weight: 500;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 700;
    background: #f8fafc;
}

.total-value {
    color: #dc2626;
}

.btn-place-order {
    width: 100%;
    margin: 20px 0;
    padding: 18px 24px;
    background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-place-order:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(26, 115, 232, 0.4);
    background: linear-gradient(135deg, #1557b0 0%, #0d47a1 100%);
    color: #ffffff !important;
}

.btn-place-order i {
    color: #ffffff;
}

.order-note {
    padding: 0 25px 20px;
    text-align: center;
    font-size: 13px;
    color: #64748b;
}

.order-note a {
    color: var(--primary-color);
}

.trust-badges {
    display: flex;
    justify-content: center;
    gap: 25px;
    padding: 20px 25px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.badge-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.badge-item i {
    font-size: 20px;
    color: var(--primary-color);
}

.badge-item span {
    font-size: 11px;
    color: #64748b;
    text-align: center;
}

/* Responsive */
@media (max-width: 991px) {
    .order-summary {
        position: static;
        margin-top: 30px;
    }
}

@media (max-width: 576px) {
    .checkout-card .card-body {
        padding: 20px 15px;
    }
    
    .method-content {
        flex-wrap: wrap;
    }
    
    .trust-badges {
        flex-wrap: wrap;
        gap: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method toggle
    const paymentMethods = document.querySelectorAll('.payment-method');
    const bankInfo = document.getElementById('bankInfo');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            paymentMethods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            const value = this.querySelector('input').value;
            if (value === 'bank_transfer') {
                bankInfo.style.display = 'block';
            } else {
                bankInfo.style.display = 'none';
            }
        });
    });
    
    // Load provinces (Vietnam API or static data)
    loadProvinces();
    
    // Province change
    document.getElementById('province').addEventListener('change', function() {
        const provinceCode = this.value;
        if (provinceCode) {
            loadDistricts(provinceCode);
        }
    });
    
    // District change
    document.getElementById('district').addEventListener('change', function() {
        const districtCode = this.value;
        if (districtCode) {
            loadWards(districtCode);
        }
    });
    
    // Apply coupon
    document.getElementById('applyCoupon').addEventListener('click', function() {
        const code = document.getElementById('couponCode').value.trim();
        if (!code) {
            Swal.fire('Lỗi', 'Vui lòng nhập mã giảm giá', 'error');
            return;
        }
        
        // API call to validate coupon
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
                document.querySelector('.discount-row').style.display = 'flex';
                document.getElementById('discountAmount').textContent = '-' + formatPrice(data.discount_amount);
                document.querySelector('input[name="discount"]').value = data.discount_amount;
                updateTotal();
                Swal.fire('Thành công', 'Đã áp dụng mã giảm giá', 'success');
            } else {
                Swal.fire('Lỗi', data.message || 'Mã giảm giá không hợp lệ', 'error');
            }
        });
    });
    
    // Form submit
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Đang xử lý...',
            text: 'Vui lòng đợi trong giây lát',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = new FormData(this);
        formData.append('action', 'create');
        
        fetch('<?= BASE_URL ?>api/orders.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Đặt hàng thành công!',
                    text: 'Mã đơn hàng của bạn là: ' + data.order_code,
                    confirmButtonText: 'Xem đơn hàng'
                }).then(() => {
                    window.location.href = '<?= BASE_URL ?>don-hang/' + data.order_id;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.'
                });
            }
        });
    });
});

// Load Vietnam provinces/districts/wards
// Using static data or Vietnam Provinces API
function loadProvinces() {
    // Example with static data (should use API in production)
    const provinces = [
        {code: '01', name: 'Hà Nội'},
        {code: '79', name: 'TP. Hồ Chí Minh'},
        {code: '48', name: 'Đà Nẵng'},
        {code: '92', name: 'Cần Thơ'},
        {code: '31', name: 'Hải Phòng'},
        // Add more provinces...
    ];
    
    const select = document.getElementById('province');
    provinces.forEach(p => {
        select.innerHTML += `<option value="${p.code}">${p.name}</option>`;
    });
}

function loadDistricts(provinceCode) {
    // Example - should fetch from API
    const districts = [
        {code: '001', name: 'Quận Ba Đình'},
        {code: '002', name: 'Quận Hoàn Kiếm'},
        {code: '003', name: 'Quận Tây Hồ'},
        // Add more districts...
    ];
    
    const select = document.getElementById('district');
    select.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
    districts.forEach(d => {
        select.innerHTML += `<option value="${d.code}">${d.name}</option>`;
    });
    
    document.getElementById('ward').innerHTML = '<option value="">Chọn Phường/Xã</option>';
}

function loadWards(districtCode) {
    // Example - should fetch from API
    const wards = [
        {code: '00001', name: 'Phường Phúc Xá'},
        {code: '00002', name: 'Phường Trúc Bạch'},
        {code: '00003', name: 'Phường Vĩnh Phúc'},
        // Add more wards...
    ];
    
    const select = document.getElementById('ward');
    select.innerHTML = '<option value="">Chọn Phường/Xã</option>';
    wards.forEach(w => {
        select.innerHTML += `<option value="${w.code}">${w.name}</option>`;
    });
}

function updateTotal() {
    const subtotal = parseFloat(document.querySelector('input[name="subtotal"]').value);
    const shipping = parseFloat(document.querySelector('input[name="shipping_fee"]').value);
    const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
    const total = subtotal + shipping - discount;
    
    document.querySelector('.total-value').textContent = formatPrice(total);
    document.querySelector('input[name="total"]').value = total;
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


