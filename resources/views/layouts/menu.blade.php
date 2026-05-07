@extends('layouts.app')

@section('title', 'Menu Del Cafe')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 text-primary">Menu Del Cafe</h1>
            <p class="lead text-muted">Nikmati berbagai pilihan makanan dan minuman terbaik kami</p>
        </div>
    </div>

    <!-- Featured Menu -->
    @if(isset($featuredMenus) && $featuredMenus->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="fas fa-fire text-warning me-2"></i>Menu Terbaik
            </h2>
            <div class="row">
                @foreach($featuredMenus as $menu)
                <div class="col-md-4 mb-4">
                    <div class="card featured-menu h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text text-muted">{{ $menu->description }}</p>
                            <p class="card-text fw-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            <small class="text-muted">
                                <i class="fas fa-tag me-1"></i>Kategori: {{ $menu->category }}
                            </small>
                            <div class="mt-3">
                                @auth
                                <button class="btn btn-outline-primary btn-sm" 
                                        onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">
                                    <i class="fas fa-cart-plus me-1"></i>Tambah ke Pesanan
                                </button>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login untuk Memesan
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Menu by Category -->
    @if(isset($menusByCategory))
        @foreach($menusByCategory as $category => $menus)
        <div class="category-section mb-5" id="{{ Str::slug($category) }}">
            <div class="row">
                <div class="col-12">
                    <h2 class="border-bottom pb-2 mb-4">
                        @php
                            $icon = match($category) {
                                'Makanan' => 'utensils',
                                'Minuman' => 'coffee',
                                'Snack' => 'cookie',
                                'Dessert' => 'ice-cream',
                                default => 'circle'
                            };
                        @endphp
                        <i class="fas fa-{{ $icon }} me-2"></i>
                        {{ $category }}
                    </h2>
                </div>
            </div>
            
            <div class="row">
                @foreach($menus as $menu)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card menu-card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $menu->name }}</h5>
                            <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($menu->description, 100) }}</p>
                            <p class="card-text fw-bold text-success">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            
                            @auth
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm w-100" 
                                        onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})">
                                    <i class="fas fa-cart-plus me-1"></i>Tambah ke Pesanan
                                </button>
                            </div>
                            @else
                            <div class="mt-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login untuk Memesan
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center py-5">
            <p class="text-muted">Menu belum tersedia.</p>
        </div>
    @endif

    @auth
    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart me-2"></i>Keranjang Pesanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="orderForm" action="{{ route('customer.orders.store') }}" method="POST">
                        @csrf
                        <div id="cartItems"></div>
                        <div class="mb-3">
                            <label for="customer_notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Catatan Khusus (opsional)
                            </label>
                            <textarea class="form-control" id="customer_notes" name="customer_notes" rows="3" placeholder="Contoh: Kurangi pedas, tambah es, dll."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Total: <span id="totalAmount" class="text-success">0</span></h5>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-1"></i>Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Cart Button -->
    <div class="floating-cart" style="position: fixed; bottom: 20px; right: 20px; z-index: 1030;">
        <button class="btn btn-primary btn-lg rounded-circle shadow-lg" onclick="showCart()" style="width: 60px; height: 60px;">
            <i class="fas fa-shopping-cart"></i>
            <span id="cartCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
        </button>
    </div>
    @endauth
</div>
@endsection

@push('scripts')
<script>
let cart = [];

function addToCart(menuId, menuName, price) {
    const existingItem = cart.find(item => item.menu_id === menuId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            menu_id: menuId,
            name: menuName,
            price: price,
            quantity: 1
        });
    }
    
    updateCartCount();
    showCart();
    
    // Show success message
    showToast('success', `${menuName} ditambahkan ke keranjang!`);
}

function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        cartCount.textContent = totalItems;
    }
}

function showCart() {
    const cartItems = document.getElementById('cartItems');
    const totalAmount = document.getElementById('totalAmount');
    
    if (!cartItems || !totalAmount) return;
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Keranjang kosong</p>
            </div>
        `;
        totalAmount.textContent = 'Rp 0';
    } else {
        let html = '';
        let total = 0;
        
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            
            html += `
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <h6 class="mb-1">${item.name}</h6>
                                <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${index}, -1)">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center" value="${item.quantity}" min="1" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(${index}, 1)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <strong class="text-success">Rp ${itemTotal.toLocaleString('id-ID')}</strong>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartItems.innerHTML = html;
        totalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Add hidden inputs for form submission
    const form = document.getElementById('orderForm');
    if (form) {
        // Remove existing hidden inputs
        document.querySelectorAll('input[name^="items"]').forEach(input => input.remove());
        
        cart.forEach((item, index) => {
            const menuIdInput = document.createElement('input');
            menuIdInput.type = 'hidden';
            menuIdInput.name = `items[${index}][menu_id]`;
            menuIdInput.value = item.menu_id;
            
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.value = item.quantity;
            
            form.appendChild(menuIdInput);
            form.appendChild(quantityInput);
        });
    }
    
    // Show modal
    const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
    cartModal.show();
}

function updateQuantity(index, change) {
    cart[index].quantity += change;
    
    if (cart[index].quantity < 1) {
        cart[index].quantity = 1;
    }
    
    updateCartCount();
    showCart();
}

function removeFromCart(index) {
    const itemName = cart[index].name;
    cart.splice(index, 1);
    updateCartCount();
    showCart();
    showToast('warning', `${itemName} dihapus dari keranjang!`);
}

function showToast(type, message) {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});
</script>
@endpush