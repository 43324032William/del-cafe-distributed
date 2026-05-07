@extends('layouts.app')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-plus me-2"></i>Buat Pesanan Baru</h2>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <form action="{{ route('customer.orders.store') }}" method="POST" id="orderForm">
            @csrf
            
            @foreach($menusByCategory as $category => $menus)
            <div class="category-section mb-4">
                <h3 class="border-bottom pb-2 mb-4">
                    <i class="fas fa-{{ $category == 'Makanan' ? 'utensils' : ($category == 'Minuman' ? 'coffee' : ($category == 'Snack' ? 'cookie' : 'ice-cream')) }} me-2"></i>
                    {{ $category }}
                </h3>
                
                <div class="row">
                    @foreach($menus as $menu)
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input menu-checkbox" type="checkbox" 
                                           name="items[{{ $menu->id }}][menu_id]" 
                                           value="{{ $menu->id }}" 
                                           id="menu{{ $menu->id }}"
                                           data-price="{{ $menu->price }}"
                                           data-name="{{ $menu->name }}">
                                    <label class="form-check-label w-100" for="menu{{ $menu->id }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $menu->name }}</h6>
                                                <p class="text-muted small mb-1">{{ $menu->description }}</p>
                                                <strong class="text-success">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="quantity-control mt-2" style="display: none;">
                                    <label class="form-label small">Jumlah:</label>
                                    <div class="input-group input-group-sm" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity({{ $menu->id }})">-</button>
                                        <input type="number" class="form-control text-center" 
                                               name="items[{{ $menu->id }}][quantity]" 
                                               id="quantity{{ $menu->id }}" 
                                               value="1" min="1" max="20" readonly>
                                        <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity({{ $menu->id }})">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="customer_notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan Khusus (opsional)
                                </label>
                                <textarea class="form-control" id="customer_notes" name="customer_notes" rows="3" placeholder="Contoh: Kurangi pedas, tambah es, dll."></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Total Pesanan: <span id="totalAmount" class="text-success">Rp 0</span></h5>
                                <button type="submit" class="btn btn-primary btn-lg" id="submitButton" disabled>
                                    <i class="fas fa-paper-plane me-1"></i>Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle quantity control when checkbox is clicked
    document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const menuId = this.value;
            const quantityControl = this.closest('.card-body').querySelector('.quantity-control');
            
            if (this.checked) {
                quantityControl.style.display = 'block';
            } else {
                quantityControl.style.display = 'none';
                document.getElementById('quantity' + menuId).value = 1;
            }
            
            calculateTotal();
            updateSubmitButton();
        });
    });
});

function incrementQuantity(menuId) {
    const input = document.getElementById('quantity' + menuId);
    input.value = parseInt(input.value) + 1;
    calculateTotal();
}

function decrementQuantity(menuId) {
    const input = document.getElementById('quantity' + menuId);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        calculateTotal();
    }
}

function calculateTotal() {
    let total = 0;
    
    document.querySelectorAll('.menu-checkbox:checked').forEach(checkbox => {
        const menuId = checkbox.value;
        const quantity = parseInt(document.getElementById('quantity' + menuId).value);
        const price = parseInt(checkbox.getAttribute('data-price'));
        
        total += price * quantity;
    });
    
    document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString();
}

function updateSubmitButton() {
    const checkedBoxes = document.querySelectorAll('.menu-checkbox:checked').length;
    const submitButton = document.getElementById('submitButton');
    
    if (checkedBoxes > 0) {
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    updateSubmitButton();
});
</script>
@endsection
@endsection