<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya - Cafe</title>
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
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 1rem;
        }
        .order-body {
            padding: 1.5rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
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
        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .customer-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 15px;
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
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<!-- Navigation -->
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
                            <span class="badge bg-info ms-1">Customer</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('menu.public') }}">
                            <i class="fas fa-utensils me-1"></i>Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.history') }}">
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
                    <!-- Fallback jika somehow user belum login -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
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
                <i class="fas fa-receipt me-3"></i>Riwayat Pesanan Saya
            </h1>
            <p class="lead">Lihat status dan detail pesanan Anda</p>
        </div>
    </div>

    <div class="container">
        <!-- Customer Info -->
        <div class="customer-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4><i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}</h4>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
                    <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ Auth::user()->phone ?? 'Belum diatur' }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-white text-dark rounded p-3 d-inline-block">
                        <h5 class="mb-0">{{ $orders->count() }}</h5>
                        <small>Total Pesanan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Statistics -->
        @if($orders->count() > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="text-warning">{{ $orders->where('status', 'pending')->count() }}</h3>
                    <p class="mb-0 text-muted">Menunggu</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="text-info">{{ $orders->where('status', 'processing')->count() }}</h3>
                    <p class="mb-0 text-muted">Diproses</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="text-success">{{ $orders->where('status', 'completed')->count() }}</h3>
                    <p class="mb-0 text-muted">Selesai</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3 class="text-danger">{{ $orders->where('status', 'cancelled')->count() }}</h3>
                    <p class="mb-0 text-muted">Dibatalkan</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Order History -->
        @if($orders->count() > 0)
            @foreach($orders as $order)
            <div class="order-card card {{ $order->status }}">
                <div class="order-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>Pesanan #{{ $order->id }}
                                @if($order->order_number)
                                    <small class="ms-2">({{ $order->order_number }})</small>
                                @endif
                            </h5>
                            <small>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $order->created_at->translatedFormat('d F Y H:i') }}
                            </small>
                        </div>
                        <div>
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
                            @else
                                <span class="status-badge">{{ $order->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="order-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
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
                                                    <i class="fas fa-utensils text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->menu->name ?? 'Menu Dihapus' }}</h6>
                                                <small class="text-muted">
                                                    {{ $item->menu->category ?? 'Tidak diketahui' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if($order->notes)
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong><i class="fas fa-sticky-note me-2"></i>Catatan:</strong> 
                        {{ $order->notes }}
                    </div>
                    @endif

                    <div class="mt-3 text-end">
                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-info text-white">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                <h3>Belum ada pesanan</h3>
                <p class="text-muted">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('menu.public') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-utensils me-2"></i>Pesan Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; 2024 Cafe. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>