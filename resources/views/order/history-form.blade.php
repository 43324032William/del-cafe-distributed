<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Pesanan - Del Cafe</title>
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
        .search-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
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
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Cek Status Pesanan</h1>
            <p class="lead mb-4">Masukkan nomor telepon untuk melihat riwayat pesanan Anda</p>
            
            <!-- Quick Features -->
            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5>Cari Pesanan</h5>
                        <p class="text-muted">Cari pesanan dengan nomor telepon dan nomor order</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5>Status Real-time</h5>
                        <p class="text-muted">Lihat status pesanan secara real-time</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h5>Detail Lengkap</h5>
                        <p class="text-muted">Akses detail lengkap setiap pesanan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="search-form">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h3><i class="fas fa-search me-2"></i>Cari Pesanan Anda</h3>
                        <p class="text-muted">Masukkan informasi di bawah untuk melihat riwayat pesanan</p>
                    </div>

                    <form method="POST" action="{{ route('order.history') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="customer_phone" class="form-label">
                                <i class="fas fa-phone me-2"></i>Nomor Telepon
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="customer_phone" name="customer_phone" 
                                   placeholder="Contoh: 08123456789" required
                                   value="{{ old('customer_phone') }}"
                                   pattern="[0-9+]{10,15}"
                                   title="Masukkan nomor telepon yang valid (10-15 digit)">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan nomor telepon yang digunakan saat memesan
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="order_id" class="form-label">
                                <i class="fas fa-receipt me-2"></i>Nomor Order (Opsional)
                            </label>
                            <input type="text" class="form-control form-control-lg" 
                                   id="order_id" name="order_id" 
                                   placeholder="Contoh: ORD-20241201123045123"
                                   value="{{ old('order_id') }}"
                                   pattern="[A-Za-z0-9-]+"
                                   title="Masukkan nomor order yang valid">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Kosongkan jika ingin melihat semua pesanan dengan nomor telepon tersebut
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-search me-2"></i>Cari Pesanan
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-3">Butuh bantuan?</p>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login Customer
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('register') }}" class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h6><i class="fas fa-question-circle me-2"></i>Mengapa harus login?</h6>
                                <p class="mb-0 small text-muted">
                                    Dengan login, Anda dapat melihat semua riwayat pesanan secara otomatis tanpa perlu mencari manual setiap kali.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        document.getElementById('customer_phone').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });

        // Format order ID input
        document.getElementById('order_id').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        });

        // Auto-focus on phone input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('customer_phone').focus();
        });
    </script>
</body>
</html>