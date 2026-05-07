<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            transition: margin-left 0.3s ease;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: fixed;
            width: 250px;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .logo-brand span,
        .sidebar.collapsed .user-info span,
        .sidebar.collapsed .user-info small {
            display: none;
        }
        .sidebar.collapsed .logo-brand {
            padding: 20px 10px;
        }
        .sidebar.collapsed .user-info {
            padding: 20px 10px;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .main-content.collapsed {
            margin-left: 70px;
        }
        .navbar-top {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .logo-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.5rem;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .user-info {
            color: #fff;
            padding: 20px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.2rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .toggle-btn:hover {
            color: #495057;
        }
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .mobile-overlay.show {
            display: block;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0 !important;
            }
            .mobile-overlay {
                display: none;
            }
            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Mobile Overlay -->
            <div class="mobile-overlay" id="mobileOverlay"></div>

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="logo-brand">
                    <i class="fas fa-coffee"></i>
                    <span>Del Cafe Admin</span>
                </div>
                
                <nav class="nav flex-column mt-4">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" 
                       href="{{ route('admin.menus.index') }}">
                        <i class="fas fa-utensils"></i>
                        <span>Kelola Menu</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.financial-report') ? 'active' : '' }}" 
                       href="{{ route('admin.financial-report') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </nav>

                <div class="user-info mt-auto">
                    <i class="fas fa-user-circle fa-2x mb-2"></i>
                    <br>
                    <span>{{ Auth::user()->name }}</span>
                    <br>
                    <small>{{ Auth::user()->email }}</small>
                    <br><br>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt"></i> 
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content" id="mainContent">
                <!-- Top Navigation -->
                <nav class="navbar navbar-expand-lg navbar-top">
                    <div class="container-fluid">
                        <button class="toggle-btn me-3" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                @yield('title', 'Dashboard')
                            </h4>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3 d-none d-md-block">
                                <i class="fas fa-calendar me-1"></i>
                                {{ now()->format('d F Y') }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>
                                    {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Check localStorage for sidebar state
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            // Apply initial state
            if (isSidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
            }
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
                
                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                
                // Handle mobile overlay
                if (window.innerWidth <= 768) {
                    if (!isCollapsed) {
                        sidebar.classList.add('show');
                        mobileOverlay.classList.add('show');
                    } else {
                        sidebar.classList.remove('show');
                        mobileOverlay.classList.remove('show');
                    }
                }
            });
            
            // Close sidebar on mobile when clicking overlay
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                mobileOverlay.classList.remove('show');
                sidebar.classList.add('collapsed');
                mainContent.classList.add('collapsed');
                localStorage.setItem('sidebarCollapsed', 'true');
            });
            
            // Auto-close sidebar on mobile when clicking a link
            if (window.innerWidth <= 768) {
                const navLinks = document.querySelectorAll('.sidebar .nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('show');
                        mobileOverlay.classList.remove('show');
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    mobileOverlay.classList.remove('show');
                }
            });
            
            // Active menu highlighting
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>