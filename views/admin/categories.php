<?php
$pageTitle = 'Quản lý danh mục';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý danh mục</h4>
            <p><?= count($categories) ?> danh mục</p>
        </div>
        <div class="header-right">
            <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Thêm danh mục
            </button>
        </div>
    </div>

    <div class="row g-3">
        <!-- Categories Table -->
        <div class="col-xl-8 col-lg-12">
            <div class="admin-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th width="60" class="d-none d-md-table-cell">Hình</th>
                                    <th>Tên danh mục</th>
                                    <th class="d-none d-lg-table-cell">Slug</th>
                                    <th class="d-none d-md-table-cell">Số SP</th>
                                    <th class="d-none d-lg-table-cell">Thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesBody">
                                <?php foreach ($categories as $category): ?>
                                    <tr data-id="<?= $category['id'] ?>">
                                        <td class="d-none d-md-table-cell">
                                            <?php if (!empty($category['image'])): ?>
                                                <img src="<?= BASE_URL . $category['image'] ?>" alt="" 
                                                     class="category-thumb">
                                            <?php else: ?>
                                                <div class="category-thumb no-image">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($category['name']) ?></strong>
                                            <?php if (!empty($category['description'])): ?>
                                                <br><small class="text-muted d-none d-sm-inline"><?= htmlspecialchars(mb_substr($category['description'], 0, 50)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none d-lg-table-cell"><code><?= htmlspecialchars($category['slug']) ?></code></td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge bg-secondary"><?= $category['product_count'] ?? 0 ?></span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <input type="number" class="form-control form-control-sm order-input" 
                                                   value="<?= $category['sort_order'] ?? 0 ?>" style="width: 70px;"
                                                   data-id="<?= $category['id'] ?>">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" 
                                                       data-id="<?= $category['id'] ?>"
                                                       <?= ($category['status'] ?? 'active') == 'active' ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-icon edit edit-btn" 
                                                        data-id="<?= $category['id'] ?>"
                                                        data-name="<?= htmlspecialchars($category['name']) ?>"
                                                        data-slug="<?= htmlspecialchars($category['slug']) ?>"
                                                        data-description="<?= htmlspecialchars($category['description'] ?? '') ?>"
                                                        data-image="<?= $category['image'] ?? '' ?>"
                                                        data-icon="<?= $category['icon'] ?? '' ?>"
                                                        title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon delete" data-id="<?= $category['id'] ?>" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                                        data-icon="<?= $category['icon'] ?? '' ?>"
                                                        title="Sửa">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-icon delete" data-id="<?= $category['id'] ?>" title="Xóa">
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
        </div>

        <!-- Category Stats -->
        <div class="col-xl-4 col-lg-12">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h6><i class="fas fa-chart-pie me-2"></i>Thống kê danh mục</h6>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h6><i class="fas fa-lightbulb me-2"></i>Hướng dẫn</h6>
                </div>
                    <h6><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h6>
                </div>
                <div class="card-body">
                    <ul class="guide-list">
                        <li>
                            <i class="fas fa-lightbulb"></i>
                            <span>Kéo thả để sắp xếp thứ tự danh mục</span>
                        </li>
                        <li>
                            <i class="fas fa-lightbulb"></i>
                            <span>Slug tự động tạo từ tên danh mục</span>
                        </li>
                        <li>
                            <i class="fas fa-lightbulb"></i>
                            <span>Tắt trạng thái để ẩn danh mục khỏi menu</span>
                        </li>
                        <li>
                            <i class="fas fa-lightbulb"></i>
                            <span>Không thể xóa danh mục đang có sản phẩm</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" placeholder="Tự động tạo từ tên">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome class)</label>
                        <input type="text" class="form-control" name="icon" placeholder="fas fa-laptop">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <div class="image-preview mt-2" id="addImagePreview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Thêm danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" enctype="multipart/form-data">
                <input type="hidden" name="category_id" id="editCategoryId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="editName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" class="form-control" name="slug" id="editSlug">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (Font Awesome class)</label>
                        <input type="text" class="form-control" name="icon" id="editIcon" placeholder="fas fa-laptop">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <div class="image-preview mt-2" id="editImagePreview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-admin-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.category-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.category-thumb.no-image {
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
}

.guide-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.guide-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}

.guide-list li:last-child {
    border-bottom: none;
}

.guide-list i {
    color: #f59e0b;
    margin-top: 3px;
}

.guide-list span {
    font-size: 14px;
    color: #64748b;
}

.order-input {
    text-align: center;
}

.image-preview {
    max-width: 200px;
}

.image-preview img {
    width: 100%;
    border-radius: 8px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Chart
    const categoryData = <?= json_encode(array_map(function($cat) {
        return [
            'name' => $cat['name'],
            'count' => $cat['product_count'] ?? 0
        ];
    }, $categories)) ?>;
    
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map(c => c.name),
            datasets: [{
                data: categoryData.map(c => c.count),
                backgroundColor: [
                    '#e53935', '#fb8c00', '#fdd835', '#43a047',
                    '#00acc1', '#3949ab', '#8e24aa', '#d81b60'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Sortable
    new Sortable(document.getElementById('categoriesBody'), {
        animation: 150,
        handle: 'tr',
        onEnd: function(evt) {
            const rows = document.querySelectorAll('#categoriesBody tr');
            const orders = [];
            rows.forEach((row, index) => {
                orders.push({
                    id: row.dataset.id,
                    order: index
                });
            });
            
            fetch('<?= BASE_URL ?>api/admin/categories.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'update_order',
                    orders: orders
                })
            });
        }
    });
    
    // Add category
    document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'create');
        
        fetch('<?= BASE_URL ?>api/admin/categories.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã thêm danh mục mới', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        });
    });
    
    // Edit button
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('editCategoryId').value = this.dataset.id;
            document.getElementById('editName').value = this.dataset.name;
            document.getElementById('editSlug').value = this.dataset.slug;
            document.getElementById('editDescription').value = this.dataset.description;
            document.getElementById('editIcon').value = this.dataset.icon;
            
            const preview = document.getElementById('editImagePreview');
            if (this.dataset.image) {
                preview.innerHTML = '<img src="<?= BASE_URL ?>' + this.dataset.image + '">';
            } else {
                preview.innerHTML = '';
            }
            
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        });
    });
    
    // Edit category
    document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'update');
        
        fetch('<?= BASE_URL ?>api/admin/categories.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Thành công', 'Đã cập nhật danh mục', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
            }
        });
    });
    
    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const categoryId = this.dataset.id;
            const status = this.checked ? 'active' : 'inactive';
            
            fetch('<?= BASE_URL ?>api/admin/categories.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'update_status',
                    category_id: categoryId,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    this.checked = !this.checked;
                    Swal.fire('Lỗi', data.message || 'Có lỗi xảy ra', 'error');
                }
            });
        });
    });
    
    // Delete category
    document.querySelectorAll('.btn-icon.delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryId = this.dataset.id;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: 'Danh mục sẽ bị xóa vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= BASE_URL ?>api/admin/categories.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({
                            action: 'delete',
                            category_id: categoryId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Đã xóa!', 'Danh mục đã được xóa.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Không thể xóa danh mục', 'error');
                        }
                    });
                }
            });
        });
    });
    
    // Order input change
    document.querySelectorAll('.order-input').forEach(input => {
        input.addEventListener('change', function() {
            const categoryId = this.dataset.id;
            const order = this.value;
            
            fetch('<?= BASE_URL ?>api/admin/categories.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'update_single_order',
                    category_id: categoryId,
                    order: parseInt(order)
                })
            });
        });
    });
    
    // Image preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const preview = this.parentElement.querySelector('.image-preview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '">';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


