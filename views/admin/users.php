<?php
$pageTitle = 'Quản lý người dùng';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý người dùng</h4>
            <p><?= $totalUsers ?> người dùng</p>
        </div>
        <div class="header-right">
            <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Thêm người dùng
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="user-stat-card">
                <div class="icon all">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info">
                    <h3><?= $totalUsers ?></h3>
                    <span>Tổng người dùng</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="user-stat-card">
                <div class="icon users">
                    <i class="fas fa-user"></i>
                </div>
                <div class="info">
                    <h3><?= $userStats['user'] ?? 0 ?></h3>
                    <span>Khách hàng</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="user-stat-card">
                <div class="icon employees">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="info">
                    <h3><?= $userStats['employee'] ?? 0 ?></h3>
                    <span>Nhân viên</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="user-stat-card">
                <div class="icon admins">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="info">
                    <h3><?= $userStats['admin'] ?? 0 ?></h3>
                    <span>Quản trị viên</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form" method="GET" action="<?= BASE_URL ?>admin">
                <input type="hidden" name="page" value="users">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Tìm kiếm theo tên, email, SĐT..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="role">
                            <option value="">Tất cả vai trò</option>
                            <option value="user" <?= ($roleFilter ?? '') == 'user' ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="employee" <?= ($roleFilter ?? '') == 'employee' ? 'selected' : '' ?>>Nhân viên</option>
                            <option value="admin" <?= ($roleFilter ?? '') == 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="sort">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="orders">Đơn hàng nhiều nhất</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-admin-primary w-100">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="admin-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Vai trò</th>
                            <th>Đơn hàng</th>
                            <th>Ngày tạo</th>
                            <th>Khóa TK</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): 
                            // Xử lý avatar URL
                            $avatarUrl = $user['avatar'] ?? null;
                            if (empty($avatarUrl)) {
                                $avatarUrl = BASE_URL . 'assets/images/default-avatar.svg';
                            } elseif (strpos($avatarUrl, 'http://') !== 0 && strpos($avatarUrl, 'https://') !== 0) {
                                $avatarUrl = BASE_URL . $avatarUrl;
                            }
                        ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="item-select" value="<?= $user['id'] ?>">
                                </td>
                                <td>
                                    <div class="user-cell">
                                        <img src="<?= $avatarUrl ?>" 
                                             alt="Avatar">
                                        <div class="user-info">
                                            <span class="name"><?= htmlspecialchars($user['name'] ?? '') ?></span>
                                            <span class="username"><?= htmlspecialchars($user['email'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="role-badge <?= $user['role'] ?>">
                                        <?= ROLES[$user['role']] ?? $user['role'] ?>
                                    </span>
                                </td>
                                <td><?= $user['order_count'] ?? 0 ?></td>
                                <td><?= formatDate($user['created_at']) ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="<?= $user['id'] ?>"
                                               <?= ($user['status'] ?? 'active') == 'active' ? 'checked' : '' ?>>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon delete" data-id="<?= $user['id'] ?>" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                <?php 
                $queryParams = [];
                if (!empty($search)) $queryParams['search'] = $search;
                if (!empty($roleFilter)) $queryParams['role'] = $roleFilter;
                if (!empty($sort)) $queryParams['sort'] = $sort;
                $queryString = http_build_query($queryParams);
                
                for ($i = 1; $i <= $totalPages; $i++): 
                    $url = "?page=users&p={$i}" . ($queryString ? "&{$queryString}" : '');
                ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $url ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm người dùng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <?= tokenField() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" name="fullname">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" required>
                                <option value="user">Khách hàng</option>
                                <option value="employee">Nhân viên</option>
                                <option value="admin">Quản trị viên</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Thêm người dùng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* User Stat Cards */
.user-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.user-stat-card .icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.user-stat-card .icon.all {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.user-stat-card .icon.users {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #fff;
}

.user-stat-card .icon.employees {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: #fff;
}

.user-stat-card .icon.admins {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: #fff;
}

.user-stat-card .info h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}

.user-stat-card .info span {
    font-size: 14px;
    color: #64748b;
}

/* User Cell */
.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-cell img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.user-cell .user-info {
    display: flex;
    flex-direction: column;
}

.user-cell .name {
    font-weight: 500;
    color: #1e293b;
}

.user-cell .username {
    font-size: 12px;
    color: #94a3b8;
}

/* Role Badge */
.role-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge.user {
    background: #d1fae5;
    color: #059669;
}

.role-badge.employee {
    background: #dbeafe;
    color: #2563eb;
}

.role-badge.admin {
    background: #fee2e2;
    color: #dc2626;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add user form
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'create');
        
        fetch('<?= BASE_URL ?>api/admin/users.php', {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã thêm người dùng mới', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        });
    });
    
    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.id;
            const status = this.checked ? 'active' : 'inactive';
            
            fetch('<?= BASE_URL ?>api/admin/users.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'update-status',
                    id: userId,
                    status: status,
                    csrf_token: '<?= getToken() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Thành công', 'Đã cập nhật tình trạng tài khoản', 'success');
                } else {
                    Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                    this.checked = !this.checked;
                }
            });
        });
    });
    
    // Delete user
    document.querySelectorAll('.btn-icon.delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.id;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: 'Người dùng sẽ bị xóa vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/admin/users.php', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id: userId,
                            csrf_token: '<?= getToken() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Người dùng đã được xóa.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Không thể xóa người dùng', 'error');
                        }
                    });
                }
            });
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


