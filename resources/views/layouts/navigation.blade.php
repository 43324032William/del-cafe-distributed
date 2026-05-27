<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        {{-- Logo/Home link --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-coffee me-2"></i>Cafe
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                {{-- Menu Public - selalu tampil --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('menu.public') }}">
                        <i class="fas fa-utensils me-1"></i>Menu
                    </a>
                </li>

                {{-- Cek Pesanan - selalu tampil untuk semua --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('order.history.form') }}">
                        <i class="fas fa-history me-1"></i>Cek Pesanan
                    </a>
                </li>

                @auth
                    {{-- User sudah login --}}
                    @if(Auth::user()->isAdmin())
                        {{-- ADMIN MENU --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>Admin Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.menus.index') }}">
                                <i class="fas fa-list me-1"></i>Kelola Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-shopping-cart me-1"></i>Kelola Pesanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.financial-report') }}">
                                <i class="fas fa-chart-bar me-1"></i>Laporan Keuangan
                            </a>
                        </li>
                    @else
                        {{-- CUSTOMER MENU --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('order.history') }}">
                                <i class="fas fa-receipt me-1"></i>Riwayat Saya
                            </a>
                        </li>
                    @endif

                    {{-- Profile menu untuk semua user --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-1"></i>Profil
                        </a>
                    </li>
                @endauth
            </ul>

            {{-- Right side navigation --}}
            <ul class="navbar-nav ms-auto">
                @auth
                    {{-- User sudah login --}}
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user-circle me-1"></i>
                            Halo, {{ Auth::user()->name }}
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-warning ms-1">Admin</span>
                            @else
                                <span class="badge bg-info ms-1">Customer</span>
                            @endif
                        </span>
                    </li>
                    <li class="nav-item">
                        {{-- Logout form --}}
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" 
                                    style="border: none; background: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                @else
                    {{-- User belum login --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Daftar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">
                            <i class="fas fa-user-shield me-1"></i>Admin
                        </a>
                    </li>
                @endauth
                
                {{-- Cart/Pesan button untuk customer --}}
                @auth
                    @if(!Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="{{ route('menu.public') }}#menu">
                            <i class="fas fa-shopping-cart me-1"></i>Pesan Menu
                        </a>
                    </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="{{ route('menu.public') }}#menu">
                            <i class="fas fa-shopping-cart me-1"></i>Pesan Menu
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>