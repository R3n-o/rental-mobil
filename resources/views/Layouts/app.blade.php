<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rental Mobil Pro - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #4e73df; border: none; border-radius: 10px; padding: 10px 20px; }
        .btn-primary:hover { background-color: #2e59d9; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-link.active { color: #fff !important; font-weight: bold; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/"><i class="fa-solid fa-car-side"></i> RENTAL PRO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @if(session()->has('token'))
                        
                        @if(session('role') === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">Armada</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('admin/bookings') ? 'active' : '' }}" href="/admin/bookings">Data Booking</a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-danger btn-sm px-3 rounded-pill" href="/logout">Logout Admin</a>
                            </li>
                        
                        @else
                            <li class="nav-item me-3">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Cari Mobil</a>
                            </li>
                            <li class="nav-item me-3">
                                <a class="nav-link {{ request()->is('bookings') ? 'active' : '' }} text-warning" href="/bookings">
                                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Order
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn btn-outline-light text-white px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-circle me-1"></i> {{ session('user')['name'] ?? 'Akun Saya' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="/bookings"><i class="fa-solid fa-receipt me-2"></i> Pesanan Saya</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="/logout"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</a></li>
                                </ul>
                            </li>
                        @endif

                    @else
                        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2 px-4 rounded-pill" href="/register">Daftar</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>