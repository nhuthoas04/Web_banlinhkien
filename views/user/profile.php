<?php
$pageTitle = 'Thông tin tài khoản';
include __DIR__ . '/../layouts/header.php';
?>

<!-- Profile Section -->
<section class="profile-section py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include __DIR__ . '/../components/account-sidebar.php'; ?>
            </div>

            <!-- Profile Content -->
            <div class="col-lg-9">
                <div class="profile-container">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php 
                            $avatarSrc = $user['avatar'] ?? null;
                            if (empty($avatarSrc)) {
                                $avatarSrc = BASE_URL . 'assets/images/default-avatar.svg';
                            } elseif (strpos($avatarSrc, 'http://') !== 0 && strpos($avatarSrc, 'https://') !== 0) {
                                $avatarSrc = BASE_URL . $avatarSrc;
                            }
                            ?>
                            <img src="<?= $avatarSrc ?>" 
                                 alt="Avatar" id="avatarPreview">
                            <label class="avatar-upload">
                                <input type="file" accept="image/*" id="avatarInput">
                                <i class="fas fa-camera"></i>
                            </label>
                        </div>
                        <div class="profile-info">
                            <h4><?= htmlspecialchars($user['name'] ?? $_SESSION['fullname'] ?? 'Người dùng') ?></h4>
                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email'] ?? '') ?></p>
                            <p><i class="fas fa-calendar-alt"></i> Thành viên từ <?= formatDate($user['created_at'] ?? date('Y-m-d')) ?></p>
                        </div>
                    </div>

                    <!-- Profile Tabs -->
                    <ul class="nav nav-tabs profile-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" 
                                    data-bs-target="#info" type="button">
                                <i class="fas fa-user"></i> Thông tin cá nhân
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="address-tab" data-bs-toggle="tab" 
                                    data-bs-target="#address" type="button">
                                <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" 
                                    data-bs-target="#password" type="button">
                                <i class="fas fa-lock"></i> Đổi mật khẩu
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        <!-- Personal Info -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form id="profileForm" class="profile-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" name="fullname" 
                                               value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" name="phone" 
                                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" name="birthday" 
                                               value="<?= $user['birthday'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Giới tính</label>
                                        <select class="form-select" name="gender">
                                            <option value="">Chọn giới tính</option>
                                            <option value="male" <?= ($user['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Nam</option>
                                            <option value="female" <?= ($user['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Nữ</option>
                                            <option value="other" <?= ($user['gender'] ?? '') == 'other' ? 'selected' : '' ?>>Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Address Book -->
                        <div class="tab-pane fade" id="address" role="tabpanel">
                            <div class="address-list">
                                <?php if (empty($user['addresses'])): ?>
                                    <div class="empty-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <p>Chưa có địa chỉ nào</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($user['addresses'] as $index => $address): ?>
                                        <div class="address-card <?= ($address['is_default'] ?? false) ? 'default' : '' ?>">
                                            <?php if ($address['is_default'] ?? false): ?>
                                                <span class="default-badge">Mặc định</span>
                                            <?php endif; ?>
                                            <div class="address-info">
                                                <h6><?= htmlspecialchars($address['name'] ?? '') ?> 
                                                    <span><?= htmlspecialchars($address['phone'] ?? '') ?></span>
                                                </h6>
                                                <p><?= htmlspecialchars($address['address']) ?></p>
                                                <p><?= htmlspecialchars($address['ward'] . ', ' . $address['district'] . ', ' . $address['province']) ?></p>
                                            </div>
                                            <div class="address-actions">
                                                <button class="btn-edit-address" data-index="<?= $index ?>">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </button>
                                                <?php if (!($address['is_default'] ?? false)): ?>
                                                    <button class="btn-set-default" data-index="<?= $index ?>">
                                                        <i class="fas fa-check"></i> Đặt mặc định
                                                    </button>
                                                    <button class="btn-delete-address" data-index="<?= $index ?>">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-add-address" data-bs-toggle="modal" data-bs-target="#addressModal">
                                <i class="fas fa-plus"></i> Thêm địa chỉ mới
                            </button>
                        </div>

                        <!-- Change Password -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form id="passwordForm" class="profile-form">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label">Mật khẩu hiện tại</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="current_password" required>
                                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Mật khẩu mới</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="new_password" 
                                                   id="newPassword" required minlength="6">
                                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2">
                                            <div class="strength-bar">
                                                <div class="strength-fill" id="strengthFill"></div>
                                            </div>
                                            <span class="strength-text" id="strengthText"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Xác nhận mật khẩu mới</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="confirm_password" required>
                                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-key"></i> Đổi mật khẩu
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addressForm">
                <div class="modal-body">
                    <input type="hidden" name="address_index" id="addressIndex" value="-1">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" name="addr_fullname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="addr_phone" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" name="addr_province" id="addrProvince" required>
                                <option value="">Chọn Tỉnh/TP</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quận/Huyện</label>
                            <select class="form-select" name="addr_district" id="addrDistrict" required>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phường/Xã</label>
                            <select class="form-select" name="addr_ward" id="addrWard" required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control" name="addr_address" 
                                   placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_default">
                                <span class="form-check-label">Đặt làm địa chỉ mặc định</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Profile Container */
.profile-container {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    overflow: hidden;
}

/* Profile Header */
.profile-header {
    display: flex;
    align-items: center;
    gap: 30px;
    padding: 30px;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fff;
}

.profile-avatar {
    position: relative;
    width: 120px;
    height: 120px;
    flex-shrink: 0;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
}

.avatar-upload {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 36px;
    height: 36px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #dc2626;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.avatar-upload input {
    display: none;
}

.profile-info {
    flex: 1;
    min-width: 0;
}

.profile-info h4 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #ffffff !important;
}

.profile-info p {
    margin: 5px 0;
    opacity: 0.9;
    font-size: 14px;
    color: #ffffff !important;
}

.profile-info i {
    width: 20px;
    margin-right: 8px;
}

/* Profile Tabs */
.profile-tabs {
    border: none;
    background: #f8fafc;
    padding: 0;
}

.profile-tabs .nav-link {
    border: none;
    padding: 18px 25px;
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 3px solid transparent;
}

.profile-tabs .nav-link.active {
    background: #fff;
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-content {
    padding: 30px;
}

/* Profile Form */
.profile-form .form-label {
    font-weight: 500;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
}

.profile-form .form-control,
.profile-form .form-select {
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
}

.profile-form .form-control:focus,
.profile-form .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
}

/* Password Strength */
.password-strength {
    display: flex;
    align-items: center;
    gap: 15px;
}

.strength-bar {
    flex: 1;
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 0;
    transition: all 0.3s;
}

.strength-text {
    font-size: 12px;
    font-weight: 500;
}

/* Address Book */
.address-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.address-card {
    padding: 20px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    position: relative;
    transition: all 0.3s;
}

.address-card:hover {
    border-color: var(--primary-color);
}

.address-card.default {
    border-color: var(--primary-color);
    background: #f0f9ff;
}

.default-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-color);
    color: #fff;
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 50px;
}

.address-info h6 {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 8px;
}

.address-info h6 span {
    font-weight: 400;
    color: #64748b;
    margin-left: 15px;
}

.address-info p {
    margin: 3px 0;
    font-size: 14px;
    color: #64748b;
}

.address-actions {
    display: flex;
    gap: 15px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.address-actions button {
    background: none;
    border: none;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
    transition: all 0.3s;
}

.address-actions .btn-edit-address:hover {
    color: var(--primary-color);
}

.address-actions .btn-delete-address:hover {
    color: #ef4444;
}

.address-actions .btn-set-default:hover {
    color: #10b981;
}

.empty-address {
    text-align: center;
    padding: 50px;
    color: #94a3b8;
}

.empty-address i {
    font-size: 48px;
    margin-bottom: 15px;
}

.btn-add-address {
    margin-top: 20px;
    padding: 12px 25px;
    background: #fff;
    border: 2px dashed var(--primary-color);
    color: var(--primary-color);
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-add-address:hover {
    background: var(--primary-color);
    color: #fff;
    border-style: solid;
}

/* Modal */
.modal-content {
    border-radius: 16px;
}

.modal-header {
    border-bottom: 1px solid #e2e8f0;
    padding: 20px 25px;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 15px 25px;
}

/* Responsive */
@media (max-width: 767px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-tabs {
        overflow-x: auto;
        display: flex;
    }
    
    .profile-tabs .nav-link {
        white-space: nowrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar upload
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            // Upload avatar
            const formData = new FormData();
            formData.append('avatar', file);
            
            fetch('<?= BASE_URL ?>api/user.php?action=upload-avatar', {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Thành công', 'Đã cập nhật ảnh đại diện', 'success');
                } else {
                    Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
            });
        }
    });
    
    // Profile form
    document.getElementById('profileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Show loading
        Swal.fire({
            title: 'Đang xử lý...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch('<?= BASE_URL ?>api/user.php?action=update-profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            
            const text = await response.text();
            console.log('Raw response:', text);
            
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e, 'Response:', text);
                Swal.fire('Lỗi', 'Server trả về dữ liệu không hợp lệ', 'error');
                return;
            }
            
            if (result.success) {
                Swal.fire('Thành công', result.message || 'Đã cập nhật thông tin', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Lỗi', result.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            Swal.fire('Lỗi', 'Không thể kết nối đến server: ' + error.message, 'error');
        }
    });
    
    // Password strength
    document.getElementById('newPassword').addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let text = '';
        let color = '';
        
        if (password.length >= 6) strength++;
        if (password.length >= 10) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        
        switch (strength) {
            case 0:
            case 1:
                text = 'Yếu';
                color = '#ef4444';
                break;
            case 2:
            case 3:
                text = 'Trung bình';
                color = '#f59e0b';
                break;
            case 4:
            case 5:
                text = 'Mạnh';
                color = '#10b981';
                break;
        }
        
        document.getElementById('strengthFill').style.width = (strength * 20) + '%';
        document.getElementById('strengthFill').style.background = color;
        document.getElementById('strengthText').textContent = text;
        document.getElementById('strengthText').style.color = color;
    });
    
    // Password form
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.querySelector('input[name="new_password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        
        if (newPassword !== confirmPassword) {
            Swal.fire('Lỗi', 'Mật khẩu xác nhận không khớp', 'error');
            return;
        }
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('<?= BASE_URL ?>api/user.php?action=change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã đổi mật khẩu', 'success');
                this.reset();
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
        });
    });
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Address form
    document.getElementById('addressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('<?= BASE_URL ?>api/user.php?action=add-address', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã lưu địa chỉ', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Lỗi', 'Không thể kết nối đến server', 'error');
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>


