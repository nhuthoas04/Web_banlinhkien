/**
 * Main JavaScript - TechShop
 * Xử lý các chức năng frontend chính
 */

// BASE_URL is defined in footer.php before this file loads

// ==================== UTILITIES ====================

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Show toast notification
function showToast(message, type = 'success') {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        alert(message);
    }
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ==================== CART FUNCTIONS ====================

const Cart = {
    // Add to cart
    add: async function(productId, quantity = 1) {
        try {
            const response = await fetch(`${BASE_URL}api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'add',
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.updateCartCount(data.cart_count);
                showToast('Đã thêm vào giỏ hàng!', 'success');
                
                // Animation
                this.animateAddToCart();
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
            
            return data;
        } catch (error) {
            console.error('Error adding to cart:', error);
            showToast('Không thể thêm vào giỏ hàng', 'error');
        }
    },
    
    // Update quantity
    update: async function(productId, quantity) {
        try {
            const response = await fetch(`${BASE_URL}api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'update',
                    product_id: productId,
                    quantity: quantity
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.updateCartCount(data.cart_count);
                this.updateCartTotal(data.cart_total);
            }
            
            return data;
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    },
    
    // Remove from cart
    remove: async function(productId) {
        try {
            const response = await fetch(`${BASE_URL}api/cart.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'remove',
                    product_id: productId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.updateCartCount(data.cart_count);
                showToast('Đã xóa sản phẩm', 'success');
            }
            
            return data;
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    },
    
    // Update cart count badge
    updateCartCount: function(count) {
        const badges = document.querySelectorAll('.cart-count');
        badges.forEach(badge => {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        });
    },
    
    // Update cart total
    updateCartTotal: function(total) {
        const totalElements = document.querySelectorAll('.cart-total');
        totalElements.forEach(el => {
            el.textContent = formatPrice(total);
        });
    },
    
    // Animate add to cart
    animateAddToCart: function() {
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            cartIcon.classList.add('bounce');
            setTimeout(() => cartIcon.classList.remove('bounce'), 500);
        }
    }
};

// ==================== WISHLIST FUNCTIONS ====================

const Wishlist = {
    toggle: async function(productId, button) {
        try {
            const response = await fetch(`${BASE_URL}api/wishlist.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'toggle',
                    product_id: productId
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                if (button) {
                    button.classList.toggle('active', data.in_wishlist);
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.className = data.in_wishlist ? 'fas fa-heart' : 'far fa-heart';
                    }
                }
                showToast(data.message, 'success');
            } else {
                if (data.require_login) {
                    window.location.href = `${BASE_URL}login`;
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                }
            }
        } catch (error) {
            console.error('Error toggling wishlist:', error);
        }
    }
};

// ==================== PRODUCT FUNCTIONS ====================

const Product = {
    // Quick view
    quickView: async function(productId) {
        try {
            const response = await fetch(`${BASE_URL}api/products.php?action=quick_view&id=${productId}`);
            const data = await response.json();
            
            if (data.success) {
                this.showQuickViewModal(data.product);
            }
        } catch (error) {
            console.error('Error loading quick view:', error);
        }
    },
    
    // Show quick view modal
    showQuickViewModal: function(product) {
        const modal = document.getElementById('quickViewModal');
        if (!modal) return;
        
        modal.querySelector('.product-name').textContent = product.name;
        modal.querySelector('.product-price').textContent = formatPrice(product.price);
        modal.querySelector('.product-image').src = BASE_URL + product.image;
        modal.querySelector('.product-description').innerHTML = product.short_description || '';
        modal.querySelector('[data-product-id]').dataset.productId = product._id;
        
        // Show original price if on sale
        const originalPrice = modal.querySelector('.original-price');
        if (product.original_price && product.original_price > product.price) {
            originalPrice.textContent = formatPrice(product.original_price);
            originalPrice.style.display = 'inline';
        } else {
            originalPrice.style.display = 'none';
        }
        
        new bootstrap.Modal(modal).show();
    }
};

// ==================== SEARCH FUNCTIONS ====================

const Search = {
    init: function() {
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        
        if (!searchInput || !searchResults) return;
        
        searchInput.addEventListener('input', debounce((e) => {
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }
            
            this.search(query);
        }, 300));
        
        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    },
    
    search: async function(query) {
        const searchResults = document.getElementById('searchResults');
        
        try {
            const response = await fetch(`${BASE_URL}api/products.php?action=search&q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success && data.products.length > 0) {
                this.showResults(data.products);
            } else {
                searchResults.innerHTML = '<div class="no-results">Không tìm thấy sản phẩm</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    },
    
    showResults: function(products) {
        const searchResults = document.getElementById('searchResults');
        
        let html = products.map(product => `
            <a href="${BASE_URL}san-pham/${product.slug}" class="search-result-item">
                <img src="${BASE_URL}${product.image}" alt="${product.name}">
                <div class="info">
                    <span class="name">${product.name}</span>
                    <span class="price">${formatPrice(product.price)}</span>
                </div>
            </a>
        `).join('');
        
        html += `<a href="${BASE_URL}san-pham?q=${encodeURIComponent(document.getElementById('searchInput').value)}" class="view-all">Xem tất cả kết quả</a>`;
        
        searchResults.innerHTML = html;
        searchResults.style.display = 'block';
    }
};

// ==================== FORM VALIDATION ====================

const FormValidation = {
    init: function() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validate(form)) {
                    e.preventDefault();
                }
            });
        });
    },
    
    validate: function(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('[required]');
        
        inputs.forEach(input => {
            this.clearError(input);
            
            if (!input.value.trim()) {
                this.showError(input, 'Vui lòng điền thông tin này');
                isValid = false;
            } else if (input.type === 'email' && !this.isValidEmail(input.value)) {
                this.showError(input, 'Email không hợp lệ');
                isValid = false;
            } else if (input.type === 'tel' && !this.isValidPhone(input.value)) {
                this.showError(input, 'Số điện thoại không hợp lệ');
                isValid = false;
            } else if (input.dataset.minlength && input.value.length < parseInt(input.dataset.minlength)) {
                this.showError(input, `Tối thiểu ${input.dataset.minlength} ký tự`);
                isValid = false;
            }
        });
        
        // Check password match
        const password = form.querySelector('[name="password"]');
        const confirmPassword = form.querySelector('[name="confirm_password"]');
        if (password && confirmPassword && password.value !== confirmPassword.value) {
            this.showError(confirmPassword, 'Mật khẩu không khớp');
            isValid = false;
        }
        
        return isValid;
    },
    
    showError: function(input, message) {
        input.classList.add('is-invalid');
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        feedback.textContent = message;
        input.parentNode.appendChild(feedback);
    },
    
    clearError: function(input) {
        input.classList.remove('is-invalid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) feedback.remove();
    },
    
    isValidEmail: function(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    
    isValidPhone: function(phone) {
        return /^(0|\+84)[0-9]{9,10}$/.test(phone.replace(/\s/g, ''));
    }
};

// ==================== IMAGE GALLERY ====================

const ImageGallery = {
    init: function() {
        const mainImage = document.querySelector('.product-main-image');
        const thumbnails = document.querySelectorAll('.product-thumbnails img');
        
        if (!mainImage || !thumbnails.length) return;
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImage.src = thumb.dataset.large || thumb.src;
                thumbnails.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
        
        // Zoom on hover
        if (mainImage.closest('.image-zoom-container')) {
            mainImage.addEventListener('mousemove', (e) => {
                const rect = mainImage.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                mainImage.style.transformOrigin = `${x}% ${y}%`;
            });
            
            mainImage.addEventListener('mouseenter', () => {
                mainImage.style.transform = 'scale(1.5)';
            });
            
            mainImage.addEventListener('mouseleave', () => {
                mainImage.style.transform = 'scale(1)';
            });
        }
    }
};

// ==================== QUANTITY INPUT ====================

const QuantityInput = {
    init: function() {
        document.querySelectorAll('.quantity-input').forEach(container => {
            const input = container.querySelector('input');
            const minusBtn = container.querySelector('.qty-minus');
            const plusBtn = container.querySelector('.qty-plus');
            
            if (!input) return;
            
            const min = parseInt(input.min) || 1;
            const max = parseInt(input.max) || 999;
            
            if (minusBtn) {
                minusBtn.addEventListener('click', () => {
                    const current = parseInt(input.value) || min;
                    if (current > min) {
                        input.value = current - 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
            
            if (plusBtn) {
                plusBtn.addEventListener('click', () => {
                    const current = parseInt(input.value) || min;
                    if (current < max) {
                        input.value = current + 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
            
            input.addEventListener('change', () => {
                let value = parseInt(input.value) || min;
                value = Math.max(min, Math.min(max, value));
                input.value = value;
            });
        });
    }
};

// ==================== STICKY HEADER ====================

const StickyHeader = {
    init: function() {
        const header = document.querySelector('.header');
        if (!header) return;
        
        let lastScrollTop = 0;
        const scrollThreshold = 100;
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > scrollThreshold) {
                header.classList.add('sticky');
                
                if (scrollTop > lastScrollTop) {
                    header.classList.add('hidden');
                } else {
                    header.classList.remove('hidden');
                }
            } else {
                header.classList.remove('sticky', 'hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }
};

// ==================== BACK TO TOP ====================

const BackToTop = {
    init: function() {
        const button = document.getElementById('backToTop');
        if (!button) return;
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                button.classList.add('visible');
            } else {
                button.classList.remove('visible');
            }
        });
        
        button.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
};

// ==================== LAZY LOADING ====================

const LazyLoad = {
    init: function() {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for browsers without IntersectionObserver
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
            });
        }
    }
};

// ==================== COUNTDOWN TIMER ====================

const Countdown = {
    init: function() {
        document.querySelectorAll('[data-countdown]').forEach(element => {
            const endTime = new Date(element.dataset.countdown).getTime();
            this.start(element, endTime);
        });
    },
    
    start: function(element, endTime) {
        const update = () => {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                element.innerHTML = 'Đã kết thúc';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            element.innerHTML = `
                <span class="countdown-item"><span class="value">${days}</span><span class="label">Ngày</span></span>
                <span class="countdown-item"><span class="value">${hours}</span><span class="label">Giờ</span></span>
                <span class="countdown-item"><span class="value">${minutes}</span><span class="label">Phút</span></span>
                <span class="countdown-item"><span class="value">${seconds}</span><span class="label">Giây</span></span>
            `;
        };
        
        update();
        setInterval(update, 1000);
    }
};

// ==================== INITIALIZE ====================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    Search.init();
    FormValidation.init();
    ImageGallery.init();
    QuantityInput.init();
    StickyHeader.init();
    BackToTop.init();
    LazyLoad.init();
    Countdown.init();
    
    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true
        });
    }
    
    // Initialize Toastr
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 3000
        };
    }
    
    // Add to cart buttons
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = parseInt(this.dataset.quantity) || 1;
            Cart.add(productId, quantity);
        });
    });
    
    // Wishlist buttons
    document.querySelectorAll('.btn-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            Wishlist.toggle(productId, this);
        });
    });
    
    // Quick view buttons
    document.querySelectorAll('.btn-quick-view').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            Product.quickView(productId);
        });
    });
    
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }
});

// Export modules for use in other scripts
window.TechShop = {
    Cart,
    Wishlist,
    Product,
    Search,
    formatPrice,
    showToast
};
