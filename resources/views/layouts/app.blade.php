@php use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Olshop Fajri</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .wrapper {
            flex: 1;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 1rem;
            height: 100vh;
            position: sticky;
            top: 0;
        }
        .main-content {
            flex-grow: 1;
            padding: 1.5rem;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Olshop Fajri') }}
                </a>
                <div class="d-flex">
                    @auth
                        <span class="me-3">{{ Auth::user()->name }}</span>
                        <a class="btn btn-sm btn-outline-danger" href="#" onclick="confirmLogout(event)">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a class="btn btn-sm btn-outline-primary me-2" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-sm btn-outline-success" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="wrapper">
            <!-- Sidebar -->
           <aside class="sidebar">
    <h5>Kategori</h5>
    <form action="{{ route('produk.index') }}" method="GET">
        <select class="form-select" name="kategori_id" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->name }}
                </option>
            @endforeach
        </select>
    </form>
</aside>


            <!-- Main content -->
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- SweetAlert & Bootstrap Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>
</html>
