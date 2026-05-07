@extends('layouts.guest') {{-- Menggunakan layout yang diasumsikan sudah ada --}}

@section('title', 'Masuk ke Akun Anda')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card shadow-lg border-0 rounded-lg">
                {{-- Card Header Kustom --}}
                <div class="card-header bg-primary text-white text-center py-4 rounded-top">
                    <i class="fas fa-lock fa-3x mb-2"></i>
                    <h4 class="fw-bold mb-0">MASUK KE AKUN</h4>
                    <p class="mb-0">Akses cepat ke riwayat pesanan dan fitur eksklusif.</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required autofocus autocomplete="username"
                                       placeholder="Masukkan alamat email Anda">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input id="password" type="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       required autocomplete="current-password"
                                       placeholder="Masukkan password Anda">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-4 d-flex justify-content-between align-items-center">
                            
                            {{-- Checkbox Remember Me --}}
                            <div class="d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label ms-2" for="remember_me">
                                    Ingat Saya
                                </label>
                            </div>
                            
                            {{-- Lupa Password --}}
                            @if (Route::has('password.request'))
                                <a class="text-primary text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            
                            {{-- Tombol Login (Mengganti x-primary-button) --}}
                            <button type="submit" class="btn btn-primary w-100 fs-5 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i> MASUK
                            </button>
                            
                        </div>
                    </form>
                </div>
                
                {{-- Tautan Pendaftaran --}}
                @if (Route::has('register'))
                <div class="card-footer text-center py-3 bg-light border-top">
                    <p class="mb-0 small text-muted">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">
                        <i class="fas fa-user-plus me-1"></i>Daftar Akun Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection