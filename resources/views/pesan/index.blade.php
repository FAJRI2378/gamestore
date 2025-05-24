@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan dari Admin - Olshop Fajri</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        nav.navbar {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .card-unread {
            border-left: 5px solid #ffc107;
            background-color: #fffbea;
        }
        .card-read {
            background-color: #ffffff;
        }
        .card-body small {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Olshop Fajri</a>
            <div class="d-flex align-items-center">
                @auth
                    <span class="me-3">👤 {{ Auth::user()->name }}</span>
                    <a href="#" class="btn btn-outline-danger btn-sm" onclick="confirmLogout(event)">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container my-5">
        <h3 class="mb-4 fw-semibold">📬 Pesan dari Admin</h3>

        @forelse($pesans as $pesan)
            <div class="card mb-3 {{ $pesan->dibaca ? 'card-read' : 'card-unread' }}">
                <div class="card-body">
                    <p class="mb-1">{{ $pesan->isi }}</p>
                    <small class="text-muted">Dikirim {{ $pesan->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                Belum ada pesan yang diterima.
            </div>
        @endforelse
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-3 mt-auto border-top">
        <small>&copy; {{ date('Y') }} Olshop Fajri. All rights reserved.</small>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>
</html>
