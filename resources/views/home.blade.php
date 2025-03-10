@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dashboard Produk Laundry</h4>
                    <a href="{{ route('keranjang.index') }}" class="btn btn-warning position-relative">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @can('create', App\Models\Produk::class)
                        <div class="mb-3">
                            <a href="{{ route('produk.create') }}" class="btn btn-success">
                                <i class="fa fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                    @endcan

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga/Kg</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $produk)
                                <tr>
                                    <td>{{ $produk->nama }}</td>
                                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-primary add-to-cart" data-id="{{ $produk->id }}">
                                            <i class="fa fa-cart-plus"></i> Beli
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada produk yang tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Notifikasi sukses -->
                    <div id="cart-message" class="alert alert-success mt-3 d-none">
                        <i class="fa fa-check-circle"></i> Produk berhasil ditambahkan ke keranjang!
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert & Script AJAX untuk menambahkan produk ke keranjang -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            let produkId = this.getAttribute('data-id');

            fetch("{{ route('keranjang.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id: produkId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').innerText = data.totalItems;

                    // SweetAlert notifikasi sukses
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Produk telah ditambahkan ke keranjang.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

@endsection
