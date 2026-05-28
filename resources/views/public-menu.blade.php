<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            scroll-behavior: smooth;
        }
        .navbar-brand {
            font-weight: bold;
            color: #8B4513 !important;
        }
        .category-section {
            margin-bottom: 3rem;
            scroll-margin-top: 80px;
        }
        .category-title {
            color: #8B4513;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
        }
        .menu-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .menu-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .menu-image-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .menu-name {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .menu-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .menu-price {
            font-weight: bold;
            color: #28a745;
            font-size: 1.2rem;
        }
        .menu-category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(139, 69, 19, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .order-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-top: 2rem;
            border-radius: 15px;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 3rem;
        }
        .quantity-input {
            width: 70px;
            text-align: center;
        }
        .menu-item {
            margin-bottom: 2rem;
        }
        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        .selected-item {
            border: 2px solid #28a745;
        }
        .category-nav {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .category-nav .nav-link {
            color: #8B4513;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .category-nav .nav-link:hover,
        .category-nav .nav-link.active {
            background: #8B4513;
            color: white;
        }
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .category-nav .nav-link {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('menu.public') }}">
                <i class="fas fa-coffee me-2"></i>Cafe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                                @if(Auth::user()->role === 'admin')
                                    <span class="badge bg-warning ms-1">Admin</span>
                                @else
                                    <span class="badge bg-info ms-1">Customer</span>
                                @endif
                            </span>
                        </li>
                        
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('order.history.form') }}">
                                    <i class="fas fa-receipt me-1"></i>Riwayat Saya
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link" style="border: none; background: none;">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login/Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Daftar Akun
                            </a>
                        </li>
                    @endauth
                    
                    <li class="nav-item">
                        <button class="btn btn-outline-primary ms-2" type="button" onclick="handleOrderButton()">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Pesan (<span id="cartCount">0</span>)
                        </button>
                    </td>
                </ul>
            </div>
        </div>
    </nav>

    <div class="category-nav">
        <div class="container">
            <div class="nav nav-pills justify-content-center" id="categoryTabs">
                <a class="nav-link active" href="#all">Semua Menu</a>
                <a class="nav-link" href="#makanan">Makanan</a>
                <a class="nav-link" href="#minuman">Minuman</a>
                <a class="nav-link" href="#snack">Snack</a>
                <a class="nav-link" href="#dessert">Dessert</a>
            </div>
        </div>
    </div>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Selamat Datang di Cafe</h1>
            <p class="lead mb-4">Nikmati berbagai menu makanan dan minuman terbaik dengan kualitas premium</p>
            <a href="#menu" class="btn btn-light btn-lg">Lihat Menu</a>
        </div>
    </div>

    <div class="container" id="menu">
        <form id="orderForm" action="{{ route('order.store') }}" method="POST">
            @csrf
            <input type="hidden" name="items" id="orderItems">
            <input type="hidden" name="notes" id="hiddenOrderNotes"> <div class="category-section" id="all">
                <h2 class="category-title">
                    <i class="fas fa-utensils me-2"></i>Semua Menu
                </h2>
                <div class="row">
                    @foreach($menus as $category => $items)
                        @foreach($items as $menu)
                        <div class="col-lg-4 col-md-6 menu-item">
                            <div class="card menu-card" id="menu-card-{{ $menu->id }}">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="menu-image">
                                @else
                                    <div class="menu-image-placeholder">
                                        <i class="fas fa-utensils fa-3x"></i>
                                    </div>
                                @endif
                                
                                <span class="menu-category-badge">
                                    {{ $categories[$menu->category] ?? ucfirst($menu->category) }}
                                </span>

                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input menu-checkbox" type="checkbox" 
                                               id="menu-{{ $menu->id }}" data-menu-id="{{ $menu->id }}"
                                               data-name="{{ $menu->name }}" data-price="{{ $menu->price }}"
                                               data-image="{{ $menu->image ? asset('storage/' . $menu->image) : '' }}"
                                               data-category="{{ $menu->category }}">
                                        <label class="form-check-label" for="menu-{{ $menu->id }}">
                                            <strong>Pilih Menu</strong>
                                        </label>
                                    </div>

                                    <h5 class="menu-name">{{ $menu->name }}</h5>
                                    <p class="menu-description">{{ $menu->description }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                        
                                        <div class="quantity-control" id="quantity-{{ $menu->id }}" style="display: none;">
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity({{ $menu->id }})">-</button>
                                                <input type="number" class="form-control quantity-input text-center" 
                                                       id="qty-{{ $menu->id }}" value="1" min="1" max="10" readonly>
                                                <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity({{ $menu->id }})">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            @foreach($menus as $category => $items)
            <div class="category-section" id="{{ $category }}">
                <h2 class="category-title">
                    <i class="fas fa-{{ $category == 'makanan' ? 'utensils' : ($category == 'minuman' ? 'glass-martini' : 'ice-cream') }} me-2"></i>
                    {{ $categories[$category] ?? ucfirst($category) }}
                </h2>
                <div class="row">
                    @foreach($items as $menu)
                    <div class="col-lg-4 col-md-6 menu-item">
                        <div class="card menu-card" id="menu-card-{{ $menu->id }}-cat">
                            @if($menu->image)
                                <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="menu-image">
                            @else
                                <div class="menu-image-placeholder">
                                    <i class="fas fa-utensils fa-3x"></i>
                                </div>
                            @endif
                            
                            <span class="menu-category-badge">
                                {{ $categories[$menu->category] ?? ucfirst($menu->category) }}
                            </span>

                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input menu-checkbox" type="checkbox" 
                                           id="menu-{{ $menu->id }}-cat" data-menu-id="{{ $menu->id }}"
                                           data-name="{{ $menu->name }}" data-price="{{ $menu->price }}"
                                           data-image="{{ $menu->image ? asset('storage/' . $menu->image) : '' }}"
                                           data-category="{{ $menu->category }}">
                                    <label class="form-check-label" for="menu-{{ $menu->id }}-cat">
                                        <strong>Pilih Menu</strong>
                                    </label>
                                </div>

                                <h5 class="menu-name">{{ $menu->name }}</h5>
                                <p class="menu-description">{{ $menu->description }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    
                                    <div class="quantity-control" id="quantity-{{ $menu->id }}-cat" style="display: none;">
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity({{ $menu->id }})">-</button>
                                            <input type="number" class="form-control quantity-input text-center" 
                                                   id="qty-{{ $menu->id }}-cat" value="1" min="1" max="10" readonly>
                                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity({{ $menu->id }})">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="text-center mt-5 mb-5">
                <button type="button" class="btn btn-primary btn-lg" onclick="handleOrderButton()">
                    <i class="fas fa-paper-plane me-2"></i>Pesan Sekarang
                </button>
            </div>
        </form>
    </div>

    <div class="order-section">
        <div class="container text-center">
            <h3 class="mb-3">Siap Memesan?</h3>
            <p class="mb-4">Pilih menu favorit Anda dan nikmati pengalaman bersantap yang tak terlupakan</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <button class="btn btn-light btn-lg" onclick="handleOrderButton()">
                    <i class="fas fa-paper-plane me-2"></i>Pesan Sekarang
                </button>
                <a href="{{ route('order.history.form') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-history me-2"></i>Cek Pesanan
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-shield me-2"></i>Login Admin
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginRequiredModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login Diperlukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Anda harus login untuk melakukan pemesanan</h5>
                    <p class="text-muted">Silakan login sebagai customer untuk memesan menu favorit Anda.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti Saja</button>
                    <a href="{{ route('login') }}" class="btn btn-primary">Login Customer</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Daftar Customer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderConfirmationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart me-2"></i>Konfirmasi Pesanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>Konfirmasi Pesanan Anda</h5>
                    </div>
                    
                    <div class="customer-info mb-4 p-3 bg-light rounded">
                        <h6><i class="fas fa-user me-2"></i>Informasi Pemesan:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama:</strong> {{ Auth::user()->name ?? 'Tidak tersedia' }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Telepon:</strong> {{ Auth::user()->phone ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>

                    <div id="selectedItemsPreview" class="mb-4"></div>

                    <div class="mb-3">
                        <label for="notes" class="form-label"><strong>Catatan Pesanan (Opsional):</strong></label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Contoh: Pedas, tanpa bawang, tambah es, dll."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-edit me-2"></i>Edit Pesanan
                    </button>
                    <button type="button" class="btn btn-success" onclick="submitOrder()">
                        <i class="fas fa-paper-plane me-2"></i>Konfirmasi Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 Cafe. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedItems = [];
        const isUserLoggedIn = @json(Auth::check());
        const userRole = @json(Auth::user()->role ?? null);

        // Category Navigation Logic
        document.querySelectorAll('#categoryTabs .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('#categoryTabs .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const targetId = this.getAttribute('href').substring(1);
                if (targetId === 'all') {
                    window.scrollTo({ top: document.getElementById('menu').offsetTop - 80, behavior: 'smooth' });
                } else {
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        window.scrollTo({ top: targetElement.offsetTop - 80, behavior: 'smooth' });
                    }
                }
            });
        });

        // Checkbox Logic
        document.querySelectorAll('.menu-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const menuId = this.dataset.menuId;
                const quantityControl = document.getElementById(`quantity-${menuId}`);
                const quantityControlCat = document.getElementById(`quantity-${menuId}-cat`);
                const menuCard = document.getElementById(`menu-card-${menuId}`);
                const menuCardCat = document.getElementById(`menu-card-${menuId}-cat`);

                if (this.checked) {
                    if (quantityControl) quantityControl.style.display = 'block';
                    if (quantityControlCat) quantityControlCat.style.display = 'block';
                    if (menuCard) menuCard.classList.add('selected-item');
                    if (menuCardCat) menuCardCat.classList.add('selected-item');
                    addToSelectedItems(menuId, this.dataset.name, parseInt(this.dataset.price), this.dataset.image, this.dataset.category);
                } else {
                    if (quantityControl) quantityControl.style.display = 'none';
                    if (quantityControlCat) quantityControlCat.style.display = 'none';
                    if (menuCard) menuCard.classList.remove('selected-item');
                    if (menuCardCat) menuCardCat.classList.remove('selected-item');
                    removeFromSelectedItems(menuId);
                }
                updateCartCount();
            });
        });

        function addToSelectedItems(menuId, name, price, image, category) {
            const existingItem = selectedItems.find(item => item.menuId === menuId);
            if (!existingItem) {
                selectedItems.push({ menuId, name, price, quantity: 1, image, category });
            }
        }

        function removeFromSelectedItems(menuId) {
            selectedItems = selectedItems.filter(item => item.menuId !== menuId);
        }

        function increaseQuantity(menuId) {
            const input = document.getElementById(`qty-${menuId}`);
            const inputCat = document.getElementById(`qty-${menuId}-cat`);
            const currentValue = parseInt(input?.value || inputCat?.value || 1);
            if (currentValue < 10) {
                if (input) input.value = currentValue + 1;
                if (inputCat) inputCat.value = currentValue + 1;
                updateItemQuantity(menuId, currentValue + 1);
            }
        }

        function decreaseQuantity(menuId) {
            const input = document.getElementById(`qty-${menuId}`);
            const inputCat = document.getElementById(`qty-${menuId}-cat`);
            const currentValue = parseInt(input?.value || inputCat?.value || 1);
            if (currentValue > 1) {
                if (input) input.value = currentValue - 1;
                if (inputCat) inputCat.value = currentValue - 1;
                updateItemQuantity(menuId, currentValue - 1);
            }
        }

        function updateItemQuantity(menuId, quantity) {
            const item = selectedItems.find(item => item.menuId === menuId);
            if (item) item.quantity = quantity;
            updateCartCount();
        }

        function updateCartCount() {
            const totalItems = selectedItems.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = totalItems;
        }

        function handleOrderButton() {
            if (selectedItems.length === 0) {
                alert('Silakan pilih minimal satu menu!');
                return;
            }

            if (!isUserLoggedIn) {
                const loginModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
                loginModal.show();
                return;
            }

            updateSelectedItemsPreview();
            const orderModal = new bootstrap.Modal(document.getElementById('orderConfirmationModal'));
            orderModal.show();
        }

        function updateSelectedItemsPreview() {
            const previewContainer = document.getElementById('selectedItemsPreview');
            let html = '<table class="table"><thead><tr><th>Menu</th><th>Harga</th><th>Jumlah</th><th>Total</th></tr></thead><tbody>';
            let grandTotal = 0;

            selectedItems.forEach(item => {
                let total = item.price * item.quantity;
                grandTotal += total;
                html += `<tr>
                    <td>${item.name}</td>
                    <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                    <td>${item.quantity}</td>
                    <td>Rp ${total.toLocaleString('id-ID')}</td>
                </tr>`;
            });

            html += `<tr><td colspan="3" class="text-end"><strong>Total Bayar:</strong></td><td><strong>Rp ${grandTotal.toLocaleString('id-ID')}</strong></td></tr></tbody></table>`;
            previewContainer.innerHTML = html;
        }

        // ==========================================================
        // 🛠️ FIX SAKTI: PROSES MENGIRIM CATATAN KE FORM SEBELUM SUBMIT
        // ==========================================================
        function submitOrder() {
            // 1. Ambil nilai teks dari textarea modal konfirmasi
            const notesValue = document.getElementById('notes').value;

            // 2. Masukkan nilai items (JSON) dan isi catatan ke form hidden input utama
            document.getElementById('orderItems').value = JSON.stringify(selectedItems);
            document.getElementById('hiddenOrderNotes').value = notesValue;

            // 3. Submit form secara standar menuju OrderController
            document.getElementById('orderForm').submit();
        }
    </script>
</body>
</html>
 