@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
@endphp

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Transaksi - Olshop Fajri</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Olshop Fajri</a>
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

    <div class="container">
        <h2 class="mb-4">Riwayat Transaksi Kamu</h2>

        @forelse ($transactions as $trx)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <p><strong>No. Resi:</strong> {{ $trx->resi }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $trx->status === 'success' ? 'success' : ($trx->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($trx->status) }}</span></p>
                    <p><strong>Tanggal:</strong> {{ $trx->created_at->format('d M Y, H:i') }}</p>

                    <a href="{{ route('transactions.print', $trx->id) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-print"></i> Cetak Struk
                    </a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada transaksi yang tercatat.</div>
        @endforelse
    </div>

    <!-- Bootstrap & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
