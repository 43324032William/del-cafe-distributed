@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-chart-line me-2"></i>Laporan Keuangan</h4>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.financial-report') }}" method="GET" class="row g-3">
                    <!-- Period Selection -->
                    <div class="col-md-3">
                        <label for="period" class="form-label">Periode</label>
                        <select name="period" id="period" class="form-select" onchange="toggleCustomDate()">
                            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Tanggal Kustom</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <!-- Custom Date Fields (Hidden by Default) -->
                    <div class="col-md-3 custom-date-field" style="display: {{ $period == 'custom' ? 'block' : 'none' }};">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ $startDate }}">
                    </div>

                    <div class="col-md-3 custom-date-field" style="display: {{ $period == 'custom' ? 'block' : 'none' }};">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="{{ $endDate }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Terapkan Filter
                        </button>
                    </div>
                </form>

                <!-- Period Info -->
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Menampilkan data dari 
                        <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> 
                        hingga 
                        <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                        @if($status != 'all')
                         - Status: <strong>{{ ucfirst($status) }}</strong>
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pendapatan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Pesanan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                                <div class="text-xs text-muted mt-1">
                                    Selesai: {{ $completedOrders }} | 
                                    Pending: {{ $pendingOrders }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Rata-rata/Hari
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $days = max(1, \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1);
                                        $avgRevenue = $totalRevenue / $days;
                                        $avgOrders = $totalOrders / $days;
                                    @endphp
                                    Rp {{ number_format($avgRevenue, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    {{ number_format($avgOrders, 1) }} pesanan/hari
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Status Pesanan
                                </div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge bg-success">{{ $completedOrders }} Selesai</span>
                                    <span class="badge bg-warning">{{ $pendingOrders }} Pending</span>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    <span class="badge bg-info">{{ $processingOrders }} Proses</span>
                                    <span class="badge bg-danger">{{ $cancelledOrders }} Batal</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Terpopuler -->
        @if($popularMenus->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>10 Menu Terpopuler
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Menu</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularMenus as $index => $menu)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $menu->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $menu->total_sold }}</span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($menu->revenue, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Transaksi -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Detail Transaksi
                </h5>
                <small class="text-muted">Total: {{ $orders->count() }} transaksi</small>
            </div>
            <div class="card-body p-0">
                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80">ID</th>
                                <th>Customer</th>
                                <th width="120">Total</th>
                                <th width="100">Status</th>
                                <th width="140">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <small class="text-muted">#{{ $order->id }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-circle text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <div class="fw-semibold">{{ Str::limit($order->customer_name, 20) }}</div>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('d/m/Y') }}<br>
                                        <span class="text-muted">{{ $order->created_at->format('H:i') }}</span>
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada transaksi</h5>
                    <p class="text-muted">Tidak ada transaksi dalam periode dan filter yang dipilih</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleCustomDate() {
    const period = document.getElementById('period').value;
    const customDateFields = document.querySelectorAll('.custom-date-field');
    
    if (period === 'custom') {
        customDateFields.forEach(field => field.style.display = 'block');
    } else {
        customDateFields.forEach(field => field.style.display = 'none');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomDate();
});
</script>

<style>
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-xs {
    font-size: 0.7rem;
}
</style>
@endsection