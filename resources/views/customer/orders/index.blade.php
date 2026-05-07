@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-receipt me-2"></i>Pesanan Saya</h2>
            <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Pesanan Baru
            </a>
        </div>

        @if($orders->count() > 0)
            @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>#{{ $order->order_number }}</strong>
                        <span class="badge {{ $order->getStatusBadgeClass() }} status-badge ms-2">
                            {{ strtoupper($order->status) }}
                        </span>
                    </div>
                    <div class="text-muted">
                        <small>{{ $order->created_at->format('d M Y H:i') }}</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Items:</h6>
                            <ul class="list-unstyled">
                                @foreach($order->orderItems as $item)
                                <li>
                                    {{ $item->menu->name }} 
                                    <span class="text-muted">x{{ $item->quantity }}</span>
                                    - <strong>{{ $item->formatted_subtotal }}</strong>
                                </li>
                                @endforeach
                            </ul>
                            @if($order->customer_notes)
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-sticky-note me-1"></i>Catatan: {{ $order->customer_notes }}
                                </small>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <strong>Nomor Antrian: </strong>
                                <span class="badge bg-primary fs-6">{{ $order->queue_number }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Total: </strong>
                                <span class="fs-5 text-success">{{ $order->formatted_total }}</span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                                @if(in_array($order->status, ['pending', 'processing']))
                                <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                        <i class="fas fa-times me-1"></i>Batalkan
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada pesanan</h4>
                <p class="text-muted">Silakan membuat pesanan pertama Anda</p>
                <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Buat Pesanan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection