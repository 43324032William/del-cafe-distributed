@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-receipt me-2"></i>Detail Pesanan #{{ $order->order_number }}
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Detail Items
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->menu->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->menu->description }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ $item->formatted_unit_price }}</td>
                                        <td class="text-end">{{ $item->formatted_subtotal }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>{{ $order->formatted_total }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if($order->customer_notes)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sticky-note me-2"></i>Catatan Khusus
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->customer_notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge {{ $order->getStatusBadgeClass() }} status-badge ms-2">
                                {{ strtoupper($order->status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Nomor Antrian:</strong>
                            <span class="badge bg-primary fs-6 ms-2">{{ $order->queue_number }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Tanggal Pesan:</strong>
                            <br>
                            <span class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Terakhir Update:</strong>
                            <br>
                            <span class="text-muted">{{ $order->updated_at->format('d M Y H:i') }}</span>
                        </div>
                        
                        @if(in_array($order->status, ['pending', 'processing']))
                        <div class="mt-4">
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100" 
                                        onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                                    <i class="fas fa-times me-1"></i>Batalkan Pesanan
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>Status Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @php
                                $statuses = [
                                    'pending' => 'Menunggu Konfirmasi',
                                    'processing' => 'Sedang Diproses',
                                    'ready' => 'Siap Diambil',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            
                            @foreach($statuses as $status => $label)
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="timeline-marker">
                                        @if(array_search($order->status, array_keys($statuses)) >= array_search($status, array_keys($statuses)))
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="far fa-circle text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content ms-3">
                                        <strong class="{{ array_search($order->status, array_keys($statuses)) >= array_search($status, array_keys($statuses)) ? 'text-dark' : 'text-muted' }}">
                                            {{ $label }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-marker {
    width: 20px;
    text-align: center;
}
</style>
@endsection