@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <!-- Statistik -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Menu
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_menus'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-utensils fa-2x text-gray-300"></i>
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
                            Menu Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_menus'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Total Pesanan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                            Pesanan Pending
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus me-2"></i>Tambah Menu
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-block">
                            <i class="fas fa-eye me-2"></i>Lihat Pesanan
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-info btn-block">
                            <i class="fas fa-utensils me-2"></i>Kelola Menu
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('admin.financial-report') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-chart-line me-2"></i>Laporan Keuangan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
            </div>
            <div class="card-body">
                @if($recent_orders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recent_orders as $order)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                <small class="text-muted">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }} rounded-pill">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada pesanan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection