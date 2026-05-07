<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Menggunakan @yield('title') agar bisa diubah di view turunan --}}
        <title>@yield('title', config('app.name', 'Restoran Apps'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Skrip Bawaan Laravel (Jika masih digunakan) -->
        {{-- @vite(['resources/js/app.js']) --}}

        <!-- === ASET BOOTSTRAP 5 DAN FONT AWESOME (PENGGANTI app.css) === -->
        
        <!-- 1. Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- 2. Font Awesome Icons (WAJIB untuk ikon di form login) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH57PUX8/t+k/lXb+h7/vT6WdD2M3OqO2B2P8nQpM+2r/O6Wv6Z6D5A5S5M5E5E5S5" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- ========================================================= -->
        
    </head>
    {{-- Mengganti kelas Tailwind dengan kelas Bootstrap (atau tanpa kelas) --}}
    <body class="bg-light"> 
        {{-- Hapus container Tailwind yang kaku, gunakan Bootstrap container/grid di view turunan --}}
        <div class="pt-5 pb-5"> 
            
            {{-- Bagian Logo / Header --}}
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none d-inline-block">
                    {{-- Anda bisa masukkan logo Anda di sini, atau teks judul --}}
                    {{-- <img src="..." alt="Logo" style="height: 60px;"> --}}
                    <h2 class="text-primary fw-bold">{{ config('app.name', 'Restoran') }}</h2>
                </a>
            </div>

            {{-- Slot Konten (WAJIB: Mengganti $slot) --}}
            @yield('content')

        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    </body>
</html>