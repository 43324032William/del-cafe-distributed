@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
@php
    // Mapping Status untuk kemudahan dan tampilan yang konsisten
    $statusMap = [
        'pending' => ['color' => 'warning', 'icon' => 'clock', 'text' => 'Menunggu'],
        'processing' => ['color' => 'info', 'icon' => 'sync-alt', 'text' => 'Diproses'],
        'completed' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Selesai'],
        'cancelled' => ['color' => 'danger', 'icon' => 'times-circle', 'text' => 'Dibatalkan']
    ];
@endphp

<div class="row">
    <div class="col-12">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-shopping-cart me-2"></i>Kelola Pesanan</h4>
        </div>

        {{-- Filter dan Pencarian --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0 text-muted">
                    <i class="fas fa-filter me-2"></i>Filter Pesanan
                </h6>
                <div class="text-muted small">
                    Total: <strong class="text-primary">{{ $orders->total() }}</strong> pesanan
                </div>
            </div>
            <div class="card-body py-3">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Cari No. Order, Customer, atau Telp..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            @foreach($statusMap as $key => $status)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $status['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-sync me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
        {{-- Akhir Filter --}}

        @if($orders->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="100">No. Pesanan</th>
                                <th>Customer</th>
                                <th width="120">Total</th>
                                <th class="d-none d-xl-table-cell">Catatan</th> {{-- Kolom Catatan (Desktop Only) --}}
                                <th width="120">Status</th>
                                <th width="140">Dibuat</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php
                                $status = $statusMap[$order->status] ?? ['color' => 'secondary', 'icon' => 'circle', 'text' => 'Tidak Diketahui'];
                                $hasNotes = !empty($order->notes);
                            @endphp
                            <tr>
                                <td>
                                    <span class="text-primary fw-semibold">{{ $order->order_number }}</span>
                                    <br>
                                    <small class="text-muted">#ID: {{ $order->id }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle text-muted me-2"></i>
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($order->customer_name, 25) }}</div>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    <br>
                                    <small class="text-muted">{{ $order->orderItems->count() }} item(s)</small>
                                </td>
                                
                                {{-- Menampilkan Catatan di Tabel Utama (Desktop Only) --}}
                                <td class="d-none d-xl-table-cell">
                                    @if ($hasNotes)
                                        <span class="badge bg-danger">ADA CATATAN</span>
                                        <small class="text-muted d-block mt-1">{{ Str::limit($order->notes, 30) }}</small>
                                    @else
                                        <small class="text-muted">Tidak Ada</small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{ $status['color'] }} py-2 px-3">
                                        <i class="fas fa-{{ $status['icon'] }} me-1"></i>
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('d/m/Y') }}<br>
                                        <span class="text-secondary">{{ $order->created_at->format('H:i') }} WIB</span>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        {{-- 1. Tombol Detail --}}
                                        <button type="button" class="btn btn-info text-white" 
                                                data-bs-toggle="modal" data-bs-target="#orderDetail{{ $order->id }}" 
                                                title="Detail Pesanan">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        {{-- 2. Tombol Edit Status --}}
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                            class="btn btn-warning text-dark" title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- 3. Tombol Print/Invoice --}}
                                        <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank"
                                            class="btn btn-secondary text-white" title="Cetak Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        {{-- 4. Tombol Delete/Hapus --}}
                                        <button type="button" class="btn btn-danger delete-order-btn" 
                                                data-id="{{ $order->id }}" title="Hapus Pesanan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Pagination --}}
            @if($orders->hasPages())
            <div class="card-footer py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }}
                    </div>
                    <div>
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        {{-- Kondisi Tidak Ada Data --}}
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada data pesanan</h5>
                <p class="text-muted mb-4">Pesanan yang dicari atau difilter tidak ditemukan.</p>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-sync me-1"></i>Reset Filter
                </a>
                @endif
            </div>
        </div>
        @endif
        
        
        {{-- ========================================================= --}}
        {{-- MODAL DETAIL PESANAN (Loop di sini) --}}
        {{-- ========================================================= --}}
        @foreach($orders as $order)
        @php
            $status = $statusMap[$order->status] ?? ['color' => 'secondary', 'icon' => 'circle', 'text' => 'Tidak Diketahui'];
        @endphp
        <div class="modal fade" id="orderDetail{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="orderDetailLabel{{ $order->id }}">
                            <i class="fas fa-receipt me-2"></i>Detail Pesanan: {{ $order->order_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="border-bottom pb-1 mb-2 text-primary"><i class="fas fa-user me-1"></i> Info Customer</h6>
                                <ul class="list-unstyled small">
                                    <li><strong>Nama:</strong> {{ $order->customer_name }}</li>
                                    <li><strong>Telepon:</strong> {{ $order->customer_phone }}</li>
                                    @if($order->customer_email)
                                    <li><strong>Email:</strong> {{ $order->customer_email }}</li>
                                    @endif
                                </ul>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="border-bottom pb-1 mb-2 text-primary"><i class="fas fa-info-circle me-1"></i> Info Umum</h6>
                                <ul class="list-unstyled small">
                                    <li><strong>No. Order:</strong> <span class="fw-semibold">{{ $order->order_number }}</span></li>
                                    <li><strong>No. Antrian:</strong> <span class="badge bg-secondary">{{ $order->queue_number ?? '-' }}</span></li>
                                    <li><strong>Status:</strong> 
                                        <span class="badge bg-{{ $status['color'] }}">{{ $status['text'] }}</span>
                                    </li>
                                    <li><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y, H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                        
                        {{-- <--- TAMPILAN CATATAN DARI CUSTOMER (Lebih Menonjol) ---> --}}
                        @if($order->notes)
                        <div class="alert alert-warning p-2 mt-3 small">
                            <h6 class="mb-1 text-danger fw-bold"><i class="fas fa-exclamation-circle me-1"></i> CATATAN DARI CUSTOMER:</h6>
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                        @endif
                        
                        <h6 class="border-bottom pb-1 mb-2 mt-3 text-primary"><i class="fas fa-list-ul me-1"></i> Items Pesanan ({{ $order->orderItems->count() }})</h6>
                        
                        @if($order->orderItems && $order->orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Menu</th>
                                        <th width="80" class="text-center">Qty</th>
                                        <th width="100" class="text-end">Harga Satuan</th>
                                        <th width="120" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            {{ $item->menu->name ?? 'Menu Tidak Ditemukan' }}
                                            @if(!$item->menu)
                                            <small class="text-danger d-block">Menu ID: {{ $item->menu_id }} (hilang)</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold text-primary">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-group-divider">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">TOTAL KESELURUHAN:</td>
                                        <td class="text-end fw-bold text-success fs-6">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle me-2"></i>Tidak ada items dalam pesanan ini.
                        </div>
                        @endif
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>Ubah Status
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
    </div>
</div>

{{-- ========================================================= --}}
{{-- STYLING DAN SCRIPT (Dipindahkan ke section 'scripts' jika Anda menggunakan layout dengan section ini) --}}
{{-- ========================================================= --}}
<style>
/* Styling Tambahan */
.table-sm th,
.table-sm td {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
.list-unstyled li {
    padding-bottom: 2px;
}
.modal-header.bg-primary {
    background-color: #007bff !important; 
}
.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
.fs-6 {
    font-size: 1rem !important;
}
.table-group-divider td {
    border-top: 2px solid #dee2e6;
}
</style>

{{-- HIDDEN FORM UNTUK DELETE (Method Spoofing) --}}
<form id="delete-order-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- JAVASCRIPT UNTUK KONFIRMASI DAN SUBMIT DELETE FORM --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-order-btn');
    const deleteForm = document.getElementById('delete-order-form');
    
    if (!deleteForm) {
        console.error("Delete form not found. Deletion feature disabled.");
        return;
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            
            // Mencari No. Pesanan dari baris tabel untuk konfirmasi yang lebih informatif
            const row = this.closest('tr');
            const orderNumberElement = row ? row.querySelector('.text-primary.fw-semibold') : null;
            const orderNumber = orderNumberElement ? orderNumberElement.textContent : 'Unknown';

            if (confirm(`Anda yakin ingin menghapus Pesanan No. ${orderNumber} (ID: ${orderId})? Aksi ini tidak dapat dibatalkan.`)) {
                
                // Route admin.orders.destroy harus sudah didefinisikan di web.php
                const destroyRoute = '{{ route('admin.orders.destroy', ':id') }}';
                deleteForm.action = destroyRoute.replace(':id', orderId);

                deleteForm.submit();
            }
        });
    });
});
</script>

@endsection