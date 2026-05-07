@extends('layouts.admin')

@section('title', 'Kelola Menu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-utensils me-2"></i>Kelola Menu</h4>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Menu
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.menus.index') }}" method="GET" class="row g-3">
                    <div class="col-md-8 col-12">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari menu..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 col-6">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($menus->count() > 0)
        <div class="card">
            <div class="card-body">
                {{-- Tambahkan overflow-auto agar tabel bisa digulir horizontal di layar kecil --}}
                <div class="table-responsive overflow-auto"> 
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="80" class="d-none d-md-table-cell">Gambar</th> {{-- Sembunyikan di HP --}}
                                <th>Nama Menu</th>
                                <th class="d-none d-sm-table-cell">Kategori</th> {{-- Sembunyikan di layar sangat kecil --}}
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="d-none d-lg-table-cell">Tanggal Dibuat</th> {{-- Sembunyikan di layar sedang/kecil --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $index => $menu)
                            <tr>
                                <td class="d-none d-md-table-cell">
                                    @if($menu->image)
                                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}" 
                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-utensils text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $menu->name }}</strong>
                                    <br>
                                    {{-- Batasi deskripsi lebih pendek di layar kecil --}}
                                    <small class="text-muted d-none d-sm-inline">{{ Str::limit($menu->description, 30) }}</small>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span class="badge bg-secondary">{{ ucfirst($menu->category) }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($menu->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                    @else
                                    <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td class="d-none d-lg-table-cell">{{ $menu->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group"> {{-- Gunakan btn-group-sm untuk hemat ruang --}}
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" 
                                            class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.toggle-availability', $menu->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $menu->is_available ? 'btn-secondary' : 'btn-info' }}" 
                                                    title="{{ $menu->is_available ? 'Set Habis' : 'Set Tersedia' }}">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" 
                                              method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus menu?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                    <div class="text-muted mb-2 mb-md-0">
                        Menampilkan {{ $menus->firstItem() }} hingga {{ $menus->lastItem() }} dari {{ $menus->total() }} menu
                    </div>
                    <div>
                        {{ $menus->links() }}
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada menu</h4>
            <p class="text-muted">Silakan tambahkan menu pertama Anda</p>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Tambah Menu Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection