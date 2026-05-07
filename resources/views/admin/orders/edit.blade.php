@extends('layouts.admin')

@section('title', 'Edit Status Pesanan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-edit me-2"></i>Edit Status Pesanan #{{ $order->id }}</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Pesanan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Customer</h6>
                            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                            @if($order->customer_email)
                            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Detail Pesanan</h6>
                            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p><strong>Status Saat Ini:</strong> 
                                <span class="badge bg-{{ [
                                    'pending' => 'warning',
                                    'processing' => 'info', 
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ][$order->status] ?? 'secondary' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Form Update Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Ubah Status Pesanan</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Items Pesanan -->
                    @if($order->orderItems && $order->orderItems->count() > 0)
                    <div class="mb-4">
                        <h6>Items Pesanan</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Quantity</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->menu_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection