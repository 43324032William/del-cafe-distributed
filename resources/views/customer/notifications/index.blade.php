@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-bell me-2"></i>Notifikasi</h2>
            @if($notifications->count() > 0)
            <form action="{{ route('customer.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="card mb-3 {{ $notification->is_read ? '' : 'border-primary' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="card-title {{ $notification->is_read ? '' : 'text-primary' }}">
                                {{ $notification->title }}
                                @if(!$notification->is_read)
                                <span class="badge bg-primary ms-2">Baru</span>
                                @endif
                            </h5>
                            <p class="card-text">{{ $notification->message }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $notification->created_at->format('d M Y H:i') }}
                            </small>
                            @if($notification->order)
                            <div class="mt-2">
                                <a href="{{ route('customer.orders.show', $notification->order->id) }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Pesanan
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="ms-3">
                            @if(!$notification->is_read)
                            <form action="{{ route('customer.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Tandai sudah dibaca">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Tidak ada notifikasi</h4>
            <p class="text-muted">Semua notifikasi telah dibaca</p>
        </div>
        @endif
    </div>
</div>
@endsection