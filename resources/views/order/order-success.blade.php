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
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="success-card card">
                    <div class="card-body text-center py-5">
                        <div class="success-icon mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1 class="display-4 fw-bold text-success mb-3">Pesanan Berhasil!</h1>
                        <p class="lead mb-4">Terima kasih telah memesan di Del Cafe. Pesanan Anda sedang diproses.</p>
                        
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-8">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Detail Pesanan</h5>
                                        <p class="mb-1"><strong>Nomor Order:</strong> {{ $order->order_number }}</p>
                                        <p class="mb-1"><strong>Nomor Antrian:</strong> {{ $order->queue_number }}</p>
                                        <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        <p class="mb-0"><strong>Status:</strong> 
                                            <span class="badge bg-warning">Menunggu</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('menu.public') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-utensils me-2"></i>Pesan Lagi
                            </a>
                            @auth
                                <a href="{{ route('user.history') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-receipt me-2"></i>Lihat Riwayat
                                </a>
                            @else
                                <a href="{{ route('order.history.form') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Cek Pesanan
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>