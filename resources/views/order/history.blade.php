<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian Pesanan - Del Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: bold;
            color: #8B4513 !important;
        }
        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            border-left: 5px solid;
            overflow: hidden;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .order-card.pending {
            border-left-color: #ffc107;
        }
        .order-card.processing {
            border-left-color: #17a2b8;
        }
        .order-card.completed {
            border-left-color: #28a745;
        }
        .order-card.cancelled {
            border-left-color: #dc3545;
        }
        .order-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
        }
        .order-body {
            padding: 1.5rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #d1edff;
            color: #0c5460;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .search-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
        }
        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e9ecef;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            border-top: 4px solid;
        }
        .stats-card.pending { border-top-color: #ffc107; }
        .stats-card.processing { border-top-color: #17a2b8; }
        .stats-card.completed { border-top-color: #28a745; }
        .stats-card.cancelled { border-top-color: #dc3545; }
        .customer-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('menu.public') }}">
            <i class="fas fa-coffee me-2"></i>Del Cafe
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <!-- User sudah login -->
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user me-1"></i>
                            {{ Auth::user()->name }}
                            <span class="badge bg-info ms-1">Customer</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('menu.public') }}">
                            <i class="fas fa-utensils me-1"></i>Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.history') }}">
                            <i class="fas fa-receipt me-1"></i>Riwayat Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="border: none; background: none;">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                @else
                    <!-- User belum login -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('menu.public') }}">
                            <i class="fas fa-utensils me-1"></i>Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('order.history.form') }}">
                            <i class="fas fa-search me-1"></i>Cek Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Daftar Customer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">
                            <i class="fas fa-user-shield me-1"></i>Login Admin
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">
                <i class="fas fa-search me-3"></i>Hasil Pencarian Pesanan
            </h1>
            <p class="lead">Berikut hasil pencarian pesanan Anda</p>
        </div>
    </div>

    <!-- Login Status Info -->
@auth
<div class="container">
    <div class="alert alert-info mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-user-check me-2"></i>
                Anda login sebagai <strong>{{ Auth::user()->name }}</strong>. 
                <a href="{{ route('user.history') }}" class="alert-link">Lihat riwayat lengkap Anda di sini</a>.
            </div>
            <span class="badge bg-success">
                <i class="fas fa-check me-1"></i>Sudah Login
            </span>
        </div>
    </div>
</div>
@else
<div class="container">
    <div class="alert alert-warning mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-user-clock me-2"></i>
                Anda sedang melihat hasil pencarian sebagai tamu. 
                <a href="{{ route('login') }}" class="alert-link">Login</a> atau 
                <a href="{{ route('register') }}" class="alert-link">Daftar</a> untuk akses lebih mudah.
            </div>
            <span class="badge bg-warning">
                <i class="fas fa-user me-1"></i>Status Tamu
            </span>
        </div>
    </div>
</div>
@endauth

    <div class="container">
        <!-- Search Form -->
        <div class="search-form">
            <h5 class="card-title mb-4">
                <i class="fas fa-search me-2"></i>Cari Pesanan Lain
            </h5>
            <form action="{{ route('order.history') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="customer_phone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" name="customer_phone" 
                               placeholder="Masukkan nomor telepon" required 
                               value="{{ $searchPhone ?? old('customer_phone') }}"
                               pattern="[0-9+]{10,15}">
                    </div>
                    <div class="col-md-5">
                        <label for="order_id" class="form-label">Nomor Order (Opsional)</label>
                        <input type="text" class="form-control" name="order_id" 
                               placeholder="Masukkan nomor order"
                               value="{{ $searchOrderId ?? old('order_id') }}"
                               pattern="[A-Za-z0-9-]+">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Results -->
        @if($orders && $orders->count() > 0)
            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card pending">
                        <h3 class="text-warning">{{ $orders->where('status', 'pending')->count() }}</h3>
                        <p class="mb-0 text-muted">Menunggu</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card processing">
                        <h3 class="text-info">{{ $orders->where('status', 'processing')->count() }}</h3>
                        <p class="mb-0 text-muted">Diproses</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card completed">
                        <h3 class="text-success">{{ $orders->where('status', 'completed')->count() }}</h3>
                        <p class="mb-0 text-muted">Selesai</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card cancelled">
                        <h3 class="text-danger">{{ $orders->where('status', 'cancelled')->count() }}</h3>
                        <p class="mb-0 text-muted">Dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Results Info -->
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        Ditemukan <strong>{{ $orders->count() }}</strong> pesanan
                        @if(isset($searchPhone))
                            untuk nomor telepon <strong>{{ $searchPhone }}</strong>
                        @endif
                        @if(isset($searchOrderId) && !empty($searchOrderId))
                            dan nomor order <strong>{{ $searchOrderId }}</strong>
                        @endif
                    </div>
                    <span class="customer-badge">
                        <i class="fas fa-user me-1"></i>Pencarian Tamu
                    </span>
                </div>
            </div>

            <!-- Orders List -->
            @foreach($orders as $order)
            <div class="order-card card {{ $order->status }}">
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-2">
                                <i class="fas fa-receipt me-2"></i>Pesanan #{{ $order->id }}
                                @if($order->order_number)
                                    <small class="ms-2 opacity-75">({{ $order->order_number }})</small>
                                @endif
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <small class="text-white-50">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $order->created_at->translatedFormat('d F Y H:i') }}
                                </small>
                                <small class="text-white-50">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $order->customer_name }}
                                </small>
                                <small class="text-white-50">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $order->customer_phone }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @if($order->status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock me-1"></i>Menunggu
                                </span>
                            @elseif($order->status == 'processing')
                                <span class="status-badge status-processing">
                                    <i class="fas fa-cog me-1"></i>Diproses
                                </span>
                            @elseif($order->status == 'completed')
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </span>
                            @elseif($order->status == 'cancelled')
                                <span class="status-badge status-cancelled">
                                    <i class="fas fa-times me-1"></i>Dibatalkan
                                </span>
                            @endif
                            <div class="mt-2">
                                <small class="text-white-50">
                                    Total: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-body">
                    <!-- Items Table -->
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->menu && $item->menu->image)
                                                <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                                     alt="{{ $item->menu->name }}" 
                                                     class="item-image me-3">
                                            @else
                                                <div class="item-image bg-secondary d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-utensils text-white fa-lg"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->menu->name ?? 'Menu Dihapus' }}</h6>
                                                @if($item->menu)
                                                    <small class="text-muted">
                                                        <i class="fas fa-tag me-1"></i>
                                                        {{ $item->menu->category ?? 'Tidak diketahui' }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-primary rounded-pill fs-6">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end align-middle">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end align-middle">
                                        <strong>Rp {{ number_format($item->price, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Pesanan:</strong></td>
                                    <td class="text-end">
                                        <h5 class="mb-0 text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Notes -->
                    @if($order->notes)
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-sticky-note text-muted mt-1 me-2"></i>
                            <div>
                                <strong class="d-block mb-1">Catatan Pesanan:</strong>
                                <p class="mb-0 text-muted">{{ $order->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Nomor Antrian: <strong>{{ $order->queue_number }}</strong>
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('order.details', $order->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>Detail Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Login Prompt -->
            <div class="card border-0 bg-light mt-4">
                <div class="card-body text-center">
                    <h5><i class="fas fa-user-plus me-2"></i>Ingin Akses Lebih Mudah?</h5>
                    <p class="mb-3">Daftar atau login untuk mengakses semua riwayat pesanan Anda secara otomatis.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                        <a href="{{ route('menu.public') }}" class="btn btn-outline-dark">
                            <i class="fas fa-utensils me-2"></i>Pesan Lagi
                        </a>
                    </div>
                </div>
            </div>

        @else
            <!-- No Results -->
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">Tidak ditemukan pesanan</h3>
                <p class="text-muted mb-4">Tidak ditemukan riwayat pesanan yang cocok dengan pencarian Anda.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('order.history.form') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo me-2"></i>Cari Ulang
                    </a>
                    <a href="{{ route('menu.public') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-utensils me-2"></i>Pesan Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login Customer
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-coffee me-2"></i>Del Cafe</h5>
                    <p class="mb-0">Menghadirkan pengalaman kuliner terbaik dengan bahan-bahan berkualitas premium.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('menu.public') }}" class="text-white text-decoration-none">Menu</a></li>
                        <li><a href="{{ route('order.history.form') }}" class="text-white text-decoration-none">Cek Pesanan</a></li>
                        <li><a href="{{ route('admin.login') }}" class="text-white text-decoration-none">Login Admin</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Contoh No. 123</li>
                        <li><i class="fas fa-phone me-2"></i> (021) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> info@delcafe.com</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Del Cafe. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format phone number input
        document.querySelector('input[name="customer_phone"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });

        // Format order ID input
        document.querySelector('input[name="order_id"]').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        });

        // Auto-expand order cards on hover for better visibility
        document.addEventListener('DOMContentLoaded', function() {
            const orderCards = document.querySelectorAll('.order-card');
            orderCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '1000';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });
        });
    </script>
</body>
</html>