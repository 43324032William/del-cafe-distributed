<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->id }} - Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .order-detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #d1edff; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .info-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('menu.public') }}">
                <i class="fas fa-coffee me-2"></i>Cafe
            </a>
            @auth
                <a href="{{ route('user.history') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                </a>
            @else
                <a href="{{ route('order.history.form') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Pencarian
                </a>
            @endauth
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="order-detail-card card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            Detail Pesanan #{{ $order->id }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Informasi Pesanan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6><i class="fas fa-info-circle me-2"></i>Informasi Pesanan</h6>
                                    <hr>
                                    <p class="mb-2"><strong>Nomor Order:</strong> {{ $order->order_number }}</p>
                                    <p class="mb-2"><strong>Nomor Antrian:</strong> {{ $order->queue_number }}</p>
                                    <p class="mb-2"><strong>Tanggal:</strong> {{ $order->created_at->translatedFormat('d F Y H:i') }}</p>
                                    <p class="mb-0"><strong>Status:</strong> 
                                        @if($order->status == 'pending')
                                            <span class="status-badge status-pending">Menunggu</span>
                                        @elseif($order->status == 'processing')
                                            <span class="status-badge status-processing">Diproses</span>
                                        @elseif($order->status == 'completed')
                                            <span class="status-badge status-completed">Selesai</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="status-badge status-cancelled">Dibatalkan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-section">
                                    <h6><i class="fas fa-user me-2"></i>Informasi Customer</h6>
                                    <hr>
                                    <p class="mb-2"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                                    <p class="mb-2"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                                    @if($order->customer_email)
                                    <p class="mb-2"><strong>Email:</strong> {{ $order->customer_email }}</p>
                                    @endif
                                    @if($order->user_id)
                                    <p class="mb-0"><strong>Tipe:</strong> <span class="badge bg-success">User Terdaftar</span></p>
                                    @else
                                    <p class="mb-0"><strong>Tipe:</strong> <span class="badge bg-warning">Tamu</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Items Pesanan -->
                        <div class="info-section">
                            <h6><i class="fas fa-list me-2"></i>Items Pesanan</h6>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered">
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
                                                             width="40" height="40" 
                                                             class="rounded me-3">
                                                    @endif
                                                    <div>
                                                        <strong>{{ $item->menu->name ?? 'Menu Dihapus' }}</strong>
                                                        @if($item->menu)
                                                            <br><small class="text-muted">{{ $item->menu->category }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-end"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        @if($order->notes)
                        <div class="info-section">
                            <h6><i class="fas fa-sticky-note me-2"></i>Catatan Pesanan</h6>
                            <hr>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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