/**
 * Admin JavaScript - TechShop
 * Xử lý các chức năng admin/employee panel
 */

// Constants
let BASE_URL = document.querySelector('meta[name="base-url"]')?.content || '/';
// Ensure BASE_URL ends with /
if (!BASE_URL.endsWith('/')) {
    BASE_URL += '/';
}

// ==================== UTILITIES ====================

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Format date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// ==================== SIDEBAR ====================

const Sidebar = {
    init: function() {
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.admin-sidebar');
        const body = document.body;
        
        if (!toggleBtn || !sidebar) return;
        
        // Toggle sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Restore state from localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
        }
        
        // Mobile sidebar
        const mobileToggle = document.getElementById('mobileSidebarToggle');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
            });
        }
        
        // Submenu toggle
        document.querySelectorAll('.has-submenu > a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const parent = link.parentElement;
                parent.classList.toggle('open');
            });
        });
    }
};

// ==================== DATA TABLE ====================

const AdminTable = {
    init: function() {
        // Initialize DataTables
        document.querySelectorAll('.admin-datatable').forEach(table => {
            if (typeof $.fn.DataTable !== 'undefined') {
                $(table).DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json'
                    },
                    pageLength: 25,
                    responsive: true,
                    order: [[0, 'desc']]
                });
            }
        });
        
        // Select all checkbox
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.item-select').forEach(cb => {
                    cb.checked = this.checked;
                });
                this.updateBulkActions();
            });
        }
        
        // Individual checkboxes
        document.querySelectorAll('.item-select').forEach(cb => {
            cb.addEventListener('change', () => this.updateBulkActions());
        });
    },
    
    // Get selected items
    getSelected: function() {
        return Array.from(document.querySelectorAll('.item-select:checked')).map(cb => cb.value);
    },
    
    // Update bulk actions visibility
    updateBulkActions: function() {
        const selected = this.getSelected();
        const bulkActions = document.querySelector('.bulk-actions');
        
        if (bulkActions) {
            bulkActions.style.display = selected.length > 0 ? 'flex' : 'none';
            bulkActions.querySelector('.selected-count').textContent = selected.length;
        }
    }
};

// ==================== CHART HELPERS ====================

const Charts = {
    colors: {
        primary: '#e53935',
        secondary: '#64748b',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        info: '#3b82f6'
    },
    
    // Create line chart
    createLineChart: function(ctx, labels, datasets, options = {}) {
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets.map((ds, i) => ({
                    ...ds,
                    borderColor: ds.borderColor || Object.values(this.colors)[i],
                    backgroundColor: ds.backgroundColor || Object.values(this.colors)[i] + '20',
                    tension: 0.4,
                    fill: true
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                ...options
            }
        });
    },
    
    // Create bar chart
    createBarChart: function(ctx, labels, data, options = {}) {
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: Object.values(this.colors),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                ...options
            }
        });
    },
    
    // Create doughnut chart
    createDoughnutChart: function(ctx, labels, data, options = {}) {
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: Object.values(this.colors),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%',
                ...options
            }
        });
    }
};

// ==================== FILE UPLOAD ====================

const FileUpload = {
    init: function() {
        document.querySelectorAll('.file-upload-zone').forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            const preview = zone.querySelector('.file-preview');
            
            if (!input) return;
            
            // Click to upload
            zone.addEventListener('click', () => input.click());
            
            // Drag & drop
            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('dragover');
            });
            
            zone.addEventListener('dragleave', () => {
                zone.classList.remove('dragover');
            });
            
            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('dragover');
                
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
            
            // Preview files
            input.addEventListener('change', () => {
                if (!preview) return;
                
                preview.innerHTML = '';
                
                Array.from(input.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const div = document.createElement('div');
                            div.className = 'preview-item';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="${file.name}">
                                <span class="file-name">${file.name}</span>
                                <button type="button" class="remove-file" data-index="${Array.from(input.files).indexOf(file)}">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        });
    }
};

// ==================== NOTIFICATIONS ====================

const Notifications = {
    init: function() {
        this.checkNewNotifications();
        setInterval(() => this.checkNewNotifications(), 60000); // Check every minute
    },
    
    checkNewNotifications: async function() {
        try {
            const response = await fetch(`${BASE_URL}api/admin/notifications.php?action=check`);
            const data = await response.json();
            
            if (data.success) {
                this.updateBadge(data.count);
                
                if (data.new_notifications && data.new_notifications.length > 0) {
                    data.new_notifications.forEach(notif => {
                        this.showDesktopNotification(notif);
                    });
                }
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    },
    
    updateBadge: function(count) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    },
    
    showDesktopNotification: function(notification) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(notification.title, {
                body: notification.message,
                icon: `${BASE_URL}assets/images/logo-icon.png`
            });
        }
    },
    
    requestPermission: function() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
};

// ==================== SEARCH ====================

const AdminSearch = {
    init: function() {
        const searchInput = document.getElementById('adminSearch');
        const searchResults = document.getElementById('adminSearchResults');
        
        if (!searchInput || !searchResults) return;
        
        let timeout;
        
        searchInput.addEventListener('input', (e) => {
            clearTimeout(timeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            timeout = setTimeout(() => this.search(query), 300);
        });
        
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    },
    
    search: async function(query) {
        const searchResults = document.getElementById('adminSearchResults');
        
        try {
            const response = await fetch(`${BASE_URL}api/admin/search.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.showResults(data.results);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    },
    
    showResults: function(results) {
        const searchResults = document.getElementById('adminSearchResults');
        
        let html = '';
        
        Object.keys(results).forEach(category => {
            if (results[category].length > 0) {
                html += `<div class="search-category"><span>${category}</span></div>`;
                
                results[category].forEach(item => {
                    html += `
                        <a href="${item.url}" class="search-result-item">
                            ${item.image ? `<img src="${BASE_URL}${item.image}" alt="">` : ''}
                            <div class="info">
                                <span class="title">${item.title}</span>
                                <span class="subtitle">${item.subtitle || ''}</span>
                            </div>
                        </a>
                    `;
                });
            }
        });
        
        if (!html) {
            html = '<div class="no-results">Không tìm thấy kết quả</div>';
        }
        
        searchResults.innerHTML = html;
        searchResults.style.display = 'block';
    }
};

// ==================== MODAL HELPERS ====================

const Modal = {
    confirm: function(options) {
        return Swal.fire({
            title: options.title || 'Xác nhận',
            text: options.text || '',
            icon: options.icon || 'question',
            showCancelButton: true,
            confirmButtonColor: options.confirmColor || '#e53935',
            cancelButtonColor: '#6c757d',
            confirmButtonText: options.confirmText || 'Xác nhận',
            cancelButtonText: options.cancelText || 'Hủy'
        });
    },
    
    success: function(title, text) {
        return Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            confirmButtonColor: '#e53935'
        });
    },
    
    error: function(title, text) {
        return Swal.fire({
            icon: 'error',
            title: title,
            text: text,
            confirmButtonColor: '#e53935'
        });
    },
    
    loading: function(title = 'Đang xử lý...') {
        return Swal.fire({
            title: title,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
};

// ==================== AJAX HELPERS ====================

const Ajax = {
    post: async function(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error('Ajax error:', error);
            throw error;
        }
    },
    
    postForm: async function(url, formData) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Ajax error:', error);
            throw error;
        }
    },
    
    get: async function(url) {
        try {
            const response = await fetch(url, {
                credentials: 'same-origin'
            });
            return await response.json();
        } catch (error) {
            console.error('Ajax error:', error);
            throw error;
        }
    }
};

// ==================== STATUS UPDATE ====================

const StatusUpdate = {
    // Update order status
    order: async function(orderId, status) {
        const result = await Modal.confirm({
            title: 'Cập nhật trạng thái?',
            text: `Đơn hàng sẽ được chuyển sang trạng thái mới`,
            confirmText: 'Cập nhật'
        });
        
        if (result.isConfirmed) {
            Modal.loading();
            
            try {
                const data = await Ajax.post(`${BASE_URL}api/admin/orders.php`, {
                    action: 'update_status',
                    order_id: orderId,
                    status: status
                });
                
                Swal.close();
                
                if (data.success) {
                    Modal.success('Thành công', 'Đã cập nhật trạng thái');
                    return true;
                } else {
                    Modal.error('Lỗi', data.message || 'Có lỗi xảy ra');
                    return false;
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
                return false;
            }
        }
        
        return false;
    },
    
    // Update product status
    product: async function(productId, status) {
        try {
            const data = await Ajax.post(`${BASE_URL}api/admin/products.php`, {
                action: 'update_status',
                product_id: productId,
                status: status
            });
            
            if (data.success) {
                toastr.success('Đã cập nhật trạng thái');
                return true;
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra');
                return false;
            }
        } catch (error) {
            toastr.error('Không thể kết nối đến server');
            return false;
        }
    },
    
    // Update user status
    user: async function(userId, status) {
        try {
            const data = await Ajax.post(`${BASE_URL}api/admin/users.php`, {
                action: 'update_status',
                user_id: userId,
                status: status
            });
            
            if (data.success) {
                toastr.success('Đã cập nhật trạng thái');
                return true;
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra');
                return false;
            }
        } catch (error) {
            toastr.error('Không thể kết nối đến server');
            return false;
        }
    }
};

// ==================== DELETE HELPERS ====================

const Delete = {
    single: async function(type, id, name = '') {
        const result = await Modal.confirm({
            title: 'Xác nhận xóa?',
            text: name ? `Bạn có chắc muốn xóa "${name}"?` : 'Dữ liệu sẽ bị xóa vĩnh viễn!',
            icon: 'warning',
            confirmText: 'Xóa',
            confirmColor: '#dc2626'
        });
        
        if (result.isConfirmed) {
            Modal.loading();
            
            try {
                const data = await Ajax.post(`${BASE_URL}api/admin/${type}.php`, {
                    action: 'delete',
                    id: id
                });
                
                Swal.close();
                
                if (data.success) {
                    await Modal.success('Đã xóa', 'Dữ liệu đã được xóa thành công');
                    return true;
                } else {
                    Modal.error('Lỗi', data.message || 'Không thể xóa');
                    return false;
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
                return false;
            }
        }
        
        return false;
    },
    
    bulk: async function(type, ids) {
        if (ids.length === 0) {
            Modal.error('Thông báo', 'Vui lòng chọn ít nhất một mục');
            return false;
        }
        
        const result = await Modal.confirm({
            title: 'Xóa hàng loạt?',
            text: `Bạn có chắc muốn xóa ${ids.length} mục đã chọn?`,
            icon: 'warning',
            confirmText: 'Xóa tất cả',
            confirmColor: '#dc2626'
        });
        
        if (result.isConfirmed) {
            Modal.loading();
            
            try {
                const data = await Ajax.post(`${BASE_URL}api/admin/${type}.php`, {
                    action: 'bulk_delete',
                    ids: ids
                });
                
                Swal.close();
                
                if (data.success) {
                    await Modal.success('Đã xóa', `Đã xóa ${ids.length} mục`);
                    return true;
                } else {
                    Modal.error('Lỗi', data.message || 'Không thể xóa');
                    return false;
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
                return false;
            }
        }
        
        return false;
    }
};

// ==================== EXPORT ====================

const Export = {
    excel: function(type, filters = {}) {
        const params = new URLSearchParams(filters);
        params.set('action', 'export');
        params.set('format', 'excel');
        
        window.location.href = `${BASE_URL}api/admin/${type}.php?${params.toString()}`;
    },
    
    pdf: function(type, id) {
        window.open(`${BASE_URL}api/admin/${type}.php?action=pdf&id=${id}`, '_blank');
    },
    
    print: function(type, id) {
        window.open(`${BASE_URL}print-${type}.php?id=${id}`, '_blank', 'width=800,height=600');
    }
};

// ==================== INITIALIZE ====================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize modules
    Sidebar.init();
    AdminTable.init();
    FileUpload.init();
    Notifications.init();
    AdminSearch.init();
    
    // Request notification permission
    Notifications.requestPermission();
    
    // Initialize toastr
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };
    }
    
    // Status toggle switches
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const type = this.dataset.type || 'products';
            const id = this.dataset.id;
            const status = this.checked ? 'active' : 'inactive';
            
            const success = await StatusUpdate[type]?.(id, status) || 
                           await StatusUpdate.product(id, status);
            
            if (!success) {
                this.checked = !this.checked;
            }
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const type = this.dataset.type || 'products';
            const id = this.dataset.id;
            const name = this.dataset.name || '';
            
            const success = await Delete.single(type, id, name);
            if (success) {
                const row = this.closest('tr') || this.closest('.item');
                if (row) row.remove();
            }
        });
    });
    
    // Bulk delete button
    const bulkDeleteBtn = document.getElementById('bulkDelete');
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', async function() {
            const type = this.dataset.type || 'products';
            const ids = AdminTable.getSelected();
            
            const success = await Delete.bulk(type, ids);
            if (success) {
                location.reload();
            }
        });
    }
    
    // Bulk activate button
    const bulkActivateBtn = document.getElementById('bulkActivate');
    if (bulkActivateBtn) {
        bulkActivateBtn.addEventListener('click', async function() {
            const type = this.dataset.type || 'products';
            const ids = AdminTable.getSelected();
            
            if (ids.length === 0) {
                Modal.error('Thông báo', 'Vui lòng chọn ít nhất một mục');
                return;
            }
            
            Modal.loading();
            try {
                const data = await Ajax.post(`${BASE_URL}api/admin/${type}.php`, {
                    action: 'bulk_status',
                    ids: ids,
                    status: 'active'
                });
                
                Swal.close();
                if (data.success) {
                    await Modal.success('Thành công', `Đã kích hoạt ${ids.length} mục`);
                    location.reload();
                } else {
                    Modal.error('Lỗi', data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
            }
        });
    }
    
    // Bulk deactivate button
    const bulkDeactivateBtn = document.getElementById('bulkDeactivate');
    if (bulkDeactivateBtn) {
        bulkDeactivateBtn.addEventListener('click', async function() {
            const type = this.dataset.type || 'products';
            const ids = AdminTable.getSelected();
            
            if (ids.length === 0) {
                Modal.error('Thông báo', 'Vui lòng chọn ít nhất một mục');
                return;
            }
            
            Modal.loading();
            try {
                const data = await Ajax.post(`${BASE_URL}api/admin/${type}.php`, {
                    action: 'bulk_status',
                    ids: ids,
                    status: 'inactive'
                });
                
                Swal.close();
                if (data.success) {
                    await Modal.success('Thành công', `Đã ngừng bán ${ids.length} mục`);
                    location.reload();
                } else {
                    Modal.error('Lỗi', data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
            }
        });
    }
    
    // Form submit with loading
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            Modal.loading('Đang xử lý...');
            
            try {
                const formData = new FormData(this);
                const data = await Ajax.postForm(this.action, formData);
                
                Swal.close();
                
                if (data.success) {
                    await Modal.success('Thành công', data.message || 'Đã lưu thành công');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    Modal.error('Lỗi', data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                Swal.close();
                Modal.error('Lỗi', 'Không thể kết nối đến server');
            }
        });
    });
    
    // Date range picker
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.date-picker', {
            locale: 'vn',
            dateFormat: 'd/m/Y'
        });
        
        flatpickr('.date-range-picker', {
            locale: 'vn',
            mode: 'range',
            dateFormat: 'd/m/Y'
        });
    }
    
    // Auto-hide alerts
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
});

// Export modules
window.Admin = {
    Charts,
    Modal,
    Ajax,
    StatusUpdate,
    Delete,
    Export,
    AdminTable,
    formatPrice,
    formatDate
};
