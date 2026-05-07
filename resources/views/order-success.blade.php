<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Del Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }
        .success-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .success-body {
            padding: 2rem;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
            min-width: 140px;
        }
        .detail-value {
            color: #6c757d;
            text-align: right;
            flex: 1;
        }
        .btn-continue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        .order-number {
            font-size: 1.2em;
            font-weight: bold;
            color: #28a745;
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            margin: 10px 0;
        }
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="success-header" style="background: linear-gradient(135deg, #ffc107, #ff9800);"> 
            <i class="fas fa-hourglass-half fa-4x mb-3"></i>
            <h2 class="mb-2">Pesanan Sedang Diproses</h2>
            <p class="mb-0">Pesanan Anda kini sedang disiapkan oleh Del Cafe</p>
        </div>
            
            <div class="success-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="order-details">
                    <h5 class="text-center mb-4">
                        <i class="fas fa-receipt me-2"></i>Detail Pesanan
                    </h5>
                    
                    <div class="text-center mb-3">
                        <div class="order-number">
                            {{ $order->order_number }}
                        </div>
                        <small class="text-muted">No. Antrian: {{ $order->queue_number }}</small>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">ID Pesanan:</span>
                        <span class="detail-value">#{{ $order->id }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Nama Pemesan:</span>
                        <span class="detail-value">{{ $order->customer_name }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Telepon:</span>
                        <span class="detail-value">{{ $order->customer_phone }}</span>
                    </div>
                    
                    @if($order->customer_email)
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $order->customer_email }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-item">
                        <span class="detail-label">Total Pembayaran:</span>
                        <span class="detail-value fw-bold text-success">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            @php
                                $statusBadges = [
                                    'pending' => 'bg-warning',
                                    'processing' => 'bg-info',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger'
                                ];
                                $statusText = [
                                    'pending' => 'Menunggu Konfirmasi',
                                    'processing' => 'Sedang Diproses',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            <span class="badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </span>
                    </div>
                    
                    @if($order->notes)
                    <div class="detail-item">
                        <span class="detail-label">Catatan:</span>
                        <span class="detail-value">{{ $order->notes }}</span>
                    </div>
                    @endif

                    <div class="detail-item">
                        <span class="detail-label">Tanggal Pesanan:</span>
                        <span class="detail-value">
                            {{ $order->created_at->translatedFormat('d F Y H:i') }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                @if($order->orderItems->count() > 0)
                <div class="order-items mt-4">
                    <h6 class="mb-3">
                        <i class="fas fa-list me-2"></i>Items Pesanan ({{ $order->orderItems->count() }})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th width="80" class="text-center">Qty</th>
                                    <th width="120" class="text-end">Harga</th>
                                    <th width="140" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                {{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}
                                                @if(!$item->menu)
                                                <br><small class="text-danger">ID: {{ $item->menu_id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold text-success fs-6">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tidak ada items dalam pesanan ini.
                </div>
                @endif

                <div class="text-center mt-4">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Pesanan Anda sedang diproses. Kami akan menghubungi Anda untuk konfirmasi.
                    </p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('menu.public') }}" class="btn btn-continue me-md-2">
                            <i class="fas fa-utensils me-2"></i>Pesan Lagi
                        </a>
                        <a href="{{ route('order.history.form') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Information (Hanya tampil di development) -->
        @if(env('APP_DEBUG') && false)
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">
                    <i class="fas fa-bug me-2"></i>Debug Information
                </h6>
            </div>
            <div class="card-body">
                <h6>Order Data:</h6>
                <pre class="bg-light p-3 rounded">{{ json_encode($order->toArray(), JSON_PRETTY_PRINT) }}</pre>
                
                <h6 class="mt-3">Order Items:</h6>
                <pre class="bg-light p-3 rounded">{{ json_encode($order->orderItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'menu_id' => $item->menu_id,
                        'menu_name' => $item->menu->name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->price * $item->quantity
                    ];
                }), JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-print functionality (optional)
        function printOrder() {
            window.print();
        }

        // Auto-print setelah 2 detik (opsional)
        // setTimeout(printOrder, 2000);

        // Simpan order number ke localStorage untuk riwayat
        localStorage.setItem('lastOrderNumber', '{{ $order->order_number }}');
        localStorage.setItem('lastOrderPhone', '{{ $order->customer_phone }}');
    </script>
</body>
</html>