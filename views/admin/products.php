<?php
$pageTitle = 'Quản lý sản phẩm';
include __DIR__ . '/../layouts/admin-header.php';
?>

<div class="admin-content">
    <!-- Header Actions -->
    <div class="content-header">
        <div class="header-left">
            <h4>Quản lý sản phẩm</h4>
            <p><?= $totalProducts ?> sản phẩm</p>
        </div>
        <div class="header-right">
            <button class="btn btn-admin-outline" id="exportBtn">
                <i class="fas fa-download"></i> Xuất Excel
            </button>
            <a href="<?= BASE_URL ?>admin?page=product-add" class="btn btn-admin-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
        <div class="card-body">
            <form id="filterForm" class="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="category">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($categoryFilter ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="brand">
                            <option value="">Tất cả thương hiệu</option>
                            <?php foreach ($brands as $brand): 
                                $brandName = is_array($brand) ? ($brand['name'] ?? '') : $brand;
                                if (empty($brandName)) continue;
                            ?>
                                <option value="<?= htmlspecialchars($brandName) ?>" <?= ($brandFilter ?? '') == $brandName ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($brandName) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?= ($statusFilter ?? '') == 'active' ? 'selected' : '' ?>>Đang bán</option>
                            <option value="inactive" <?= ($statusFilter ?? '') == 'inactive' ? 'selected' : '' ?>>Ngừng bán</option>
                            <option value="low_stock" <?= ($statusFilter ?? '') == 'low_stock' ? 'selected' : '' ?>>Sắp hết hàng</option>
                            <option value="out_stock" <?= ($statusFilter ?? '') == 'out_stock' ? 'selected' : '' ?>>Hết hàng</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="sort">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="price_asc">Giá tăng dần</option>
                            <option value="price_desc">Giá giảm dần</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="best_selling">Bán chạy nhất</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-admin-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="admin-card" style="margin-top: 20px;">
        <div class="card-body p-0">
            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không có sản phẩm nào</p>
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table" id="productsTable">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Sản phẩm</th>
                            <th>SKU</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Đã bán</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="item-select" value="<?= $product['id'] ?>">
                                </td>
                                <td>
                                    <div class="product-cell">
                                        <?php 
                                        $imgPath = $product['primary_image'] ?? '';
                                        $hasImage = !empty($imgPath);
                                        if ($hasImage && strpos($imgPath, 'http') !== 0) {
                                            $imgPath = BASE_URL . $imgPath;
                                        }
                                        ?>
                                        <?php if ($hasImage): ?>
                                        <img src="<?= $imgPath ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>"
                                             class="product-thumb"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="product-thumb-placeholder" style="display:none;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <?php else: ?>
                                        <div class="product-thumb-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div class="product-info">
                                            <a href="<?= BASE_URL ?>admin?page=product-edit&id=<?= $product['id'] ?>" 
                                               class="product-name"><?= htmlspecialchars($product['name']) ?></a>
                                            <span class="product-brand"><?= htmlspecialchars($product['brand'] ?? '') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($product['sku'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                        <span class="price-sale"><?= formatPrice($product['sale_price']) ?></span>
                                        <span class="price-original"><?= formatPrice($product['price']) ?></span>
                                    <?php else: ?>
                                        <span class="price-current"><?= formatPrice($product['price']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $stockClass = 'normal';
                                    if ($product['stock'] <= 0) $stockClass = 'out';
                                    elseif ($product['stock'] <= 10) $stockClass = 'low';
                                    ?>
                                    <span class="stock-badge <?= $stockClass ?>"><?= $product['stock'] ?></span>
                                </td>
                                <td><?= $product['sold_count'] ?? 0 ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="<?= $product['id'] ?>"
                                               <?= ($product['status'] ?? 'active') == 'active' ? 'checked' : '' ?>>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>san-pham/<?= $product['slug'] ?>" 
                                           class="btn-icon" title="Xem" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>admin?page=product-edit&id=<?= $product['id'] ?>" 
                                           class="btn-icon edit" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn-icon delete" title="Xóa" 
                                                data-id="<?= $product['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions" style="display: none;">
        <span class="selected-count">0 sản phẩm đã chọn</span>
        <div class="actions">
            <button class="btn btn-outline-success" id="bulkActivate" data-type="products">
                <i class="fas fa-check"></i> Kích hoạt
            </button>
            <button class="btn btn-outline-warning" id="bulkDeactivate" data-type="products">
                <i class="fas fa-ban"></i> Ngừng bán
            </button>
            <button class="btn btn-outline-danger" id="bulkDelete" data-type="products">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav class="admin-pagination">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=products&p=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<style>
/* Content Header */
.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.content-header h4 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.content-header p {
    color: #64748b;
    margin: 5px 0 0;
}

.header-right {
    display: flex;
    gap: 10px;
}

/* Filter Form */
.filter-form .form-control,
.filter-form .form-select {
    border-radius: 10px;
    padding: 10px 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.filter-form .form-select {
    padding-right: 35px;
    background-position: right 10px center;
}

/* Product Cell */
.product-cell {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-cell img.product-thumb {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    background: #f1f5f9;
}

.product-thumb-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 20px;
    flex-shrink: 0;
}

.product-info {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 500;
    color: #1e293b;
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-name:hover {
    color: var(--admin-primary);
}

.product-brand {
    font-size: 12px;
    color: #94a3b8;
}

/* Price */
.price-sale {
    font-weight: 600;
    color: #dc2626;
}

.price-original {
    font-size: 12px;
    color: #94a3b8;
    text-decoration: line-through;
    display: block;
}

.price-current {
    font-weight: 600;
    color: #1e293b;
}

/* Stock Badge */
.stock-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.stock-badge.normal {
    background: #d1fae5;
    color: #059669;
}

.stock-badge.low {
    background: #fef3c7;
    color: #d97706;
}

.stock-badge.out {
    background: #fee2e2;
    color: #dc2626;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

/* Bulk Actions */
.bulk-actions {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #1e293b;
    color: #fff;
    padding: 15px 25px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    z-index: 100;
}

.bulk-actions .selected-count {
    font-size: 14px;
}

.bulk-actions .actions {
    display: flex;
    gap: 10px;
}

.bulk-actions .btn {
    padding: 8px 15px;
    font-size: 13px;
    border-radius: 8px;
}

/* Status Toggle */
.status-toggle {
    width: 42px;
    height: 22px;
    cursor: pointer;
}

.status-toggle:checked {
    background-color: #10b981;
    border-color: #10b981;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.item-select').forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkActions();
    });
    
    // Individual select
    document.querySelectorAll('.item-select').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const selected = document.querySelectorAll('.item-select:checked');
        const bulkActions = document.getElementById('bulkActions');
        
        if (selected.length > 0) {
            bulkActions.style.display = 'flex';
            bulkActions.querySelector('.selected-count').textContent = selected.length + ' sản phẩm đã chọn';
        } else {
            bulkActions.style.display = 'none';
        }
    }
    
    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 'active' : 'inactive';
            
            fetch('<?= BASE_URL ?>api/admin/products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    action: 'update_status',
                    id: id,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('Đã cập nhật trạng thái');
                } else {
                    toastr.error(data.message || 'Có lỗi xảy ra');
                    this.checked = !this.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Không thể kết nối đến server');
                this.checked = !this.checked;
            });
        });
    });
    
    // Delete product
    document.querySelectorAll('.btn-icon.delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: 'Sản phẩm sẽ bị xóa vĩnh viễn!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteProduct(id);
                }
            });
        });
    });
    
    function deleteProduct(id) {
        fetch('<?= BASE_URL ?>api/admin/products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Đã xóa!', 'Sản phẩm đã được xóa.', 'success')
                .then(() => location.reload());
            } else {
                Swal.fire('Lỗi!', data.message || 'Không thể xóa sản phẩm', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Lỗi!', 'Không thể kết nối đến server', 'error');
        });
    }
    
    // Bulk delete
    document.getElementById('bulkDelete').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.item-select:checked')).map(cb => cb.value);
        
        if (selected.length === 0) return;
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Xóa ${selected.length} sản phẩm đã chọn?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                bulkAction('delete', selected);
            }
        });
    });
    
    // Bulk activate/deactivate
    document.getElementById('bulkActivate').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.item-select:checked')).map(cb => cb.value);
        bulkAction('activate', selected);
    });
    
    document.getElementById('bulkDeactivate').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.item-select:checked')).map(cb => cb.value);
        bulkAction('deactivate', selected);
    });
    
    function bulkAction(action, ids) {
        // Convert ids to integers
        const intIds = ids.map(id => parseInt(id, 10));
        
        // Use XMLHttpRequest for better cookie handling
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= BASE_URL ?>api/admin/products.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.withCredentials = true;
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('Response status:', xhr.status);
                console.log('Response text:', xhr.responseText);
                
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            Swal.fire('Thành công!', data.message, 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Lỗi!', data.message || 'Có lỗi xảy ra', 'error');
                        }
                    } catch(e) {
                        console.error('JSON parse error:', e);
                        Swal.fire('Lỗi!', 'Phản hồi không hợp lệ từ server', 'error');
                    }
                } else {
                    Swal.fire('Lỗi!', 'Lỗi kết nối: ' + xhr.status, 'error');
                }
            }
        };
        
        xhr.onerror = function() {
            console.error('XHR Error');
            Swal.fire('Lỗi!', 'Không thể kết nối đến server', 'error');
        };
        
        xhr.send(JSON.stringify({
            action: 'bulk_' + action,
            ids: intIds
        }));
    }
});
</script>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


