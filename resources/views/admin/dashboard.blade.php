@extends('layouts.admin')

@section('title', 'Admin Dashboard - Del Cafe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Admin Dashboard</h1>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Menu</h5>
                            <h2>{{ \App\Models\Menu::count() }}</h2>
                            <a href="{{ route('admin.menus.index') }}" class="text-white">Kelola Menu</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pesanan</h5>
                            <h2>{{ \App\Models\Order::count() }}</h2>
                            <a href="{{ route('admin.orders.index') }}" class="text-white">Kelola Pesanan</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pesanan Baru</h5>
                            <h2>{{ \App\Models\Order::where('status', 'pending')->count() }}</h2>
                            <a href="{{ route('admin.orders.index') }}" class="text-white">Lihat Pesanan</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary me-2">
                                <i class="fas fa-plus me-1"></i>Tambah Menu Baru
                            </a>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-success me-2">
                                <i class="fas fa-utensils me-1"></i>Kelola Semua Menu
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-warning">
                                <i class="fas fa-shopping-cart me-1"></i>Kelola Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection