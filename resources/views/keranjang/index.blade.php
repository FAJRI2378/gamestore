@php use Illuminate\Support\Facades\Route; @endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Olshop Fajri</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border-radius: 0.75rem;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn i {
            margin-right: 6px;
        }
        footer {
            padding: 1rem 0;
            text-align: center;
            font-size: 0.875rem;
            border-top: 1px solid #dee2e6;
            margin-top: 3rem;
            background-color: #fff;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Olshop Fajri</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <h2 class="mb-4">🛒 Keranjang Belanja</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(!empty($cart))
            <div class="table-responsive shadow-sm bg-white p-3 rounded">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php $subtotal = $item['harga'] * $item['quantity']; $total += $subtotal; @endphp
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td>
                                    <input type="number" class="form-control quantity" data-id="{{ $id }}" value="{{ $item['quantity'] }}" min="1">
                                </td>
                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-item" data-id="{{ $id }}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <h4>Total: <strong class="text-success">Rp {{ number_format($total, 0, ',', '.') }}</strong></h4>

                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-warning clear-cart">
                        <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('keranjang.checkout') }}" class="btn btn-success checkout-btn" id="btn-checkout">
                        <i class="fas fa-credit-card"></i> Checkout
                    </a>
                </div>
            </div>
        @else
            <div class="alert alert-info">Keranjang belanja Anda masih kosong.</div>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-store"></i> Belanja Sekarang
            </a>
        @endif
    </div>

    <footer class="bg-light text-muted">
        &copy; {{ date('Y') }} Olshop Fajri - All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const totalBelanja = @json($total ?? 0);

        document.addEventListener('DOMContentLoaded', function () {
            // Update quantity
            document.querySelectorAll('.quantity').forEach(input => {
                input.addEventListener('change', function () {
                    const id = this.dataset.id;
                    const quantity = this.value;

                    fetch("{{ route('keranjang.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id, quantity })
                    }).then(res => res.json()).then(data => {
                        if (data.success) location.reload();
                        else Swal.fire("Gagal", "Gagal memperbarui jumlah.", "error");
                    });
                });
            });

            // Checkout Confirmation
            document.getElementById('btn-checkout')?.addEventListener('click', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Lanjut ke Checkout?",
                    html: "Total belanja Anda: <strong>Rp " + totalBelanja.toLocaleString('id-ID') + "</strong>",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Checkout",
                    cancelButtonText: "Batal"
                }).then(result => {
                    if (result.isConfirmed) window.location.href = this.href;
                });
            });

            // Remove item
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;

                    Swal.fire({
                        title: "Hapus produk ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Hapus",
                        cancelButtonText: "Batal"
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch("{{ route('keranjang.remove') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ id })
                            }).then(res => res.json()).then(data => {
                                if (data.success) location.reload();
                                else Swal.fire("Gagal", "Tidak bisa menghapus produk.", "error");
                            });
                        }
                    });
                });
            });

            // Clear cart
            document.querySelector('.clear-cart')?.addEventListener('click', function () {
                Swal.fire({
                    title: "Kosongkan keranjang?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal"
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch("{{ route('keranjang.clear') }}", {
                            method: "POST",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        }).then(res => res.json()).then(data => {
                            if (data.success) location.reload();
                            else Swal.fire("Gagal", "Tidak bisa mengosongkan keranjang.", "error");
                        });
                    }
                });
            });
        });
    </script>

</body>
</html>
