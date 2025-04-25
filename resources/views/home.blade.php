@extends('layouts.user')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Produk</h4>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('transactions.index') }}" class="btn btn-light me-2">
                <i class="fa fa-history"></i> Riwayat
            </a>
            <a href="{{ route('keranjang.index') }}" class="btn btn-warning position-relative">
                <i class="fa fa-shopping-cart"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @can('create', App\Models\Produk::class)
        <div class="mb-3">
            <a href="{{ route('produk.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Produk
            </a>
        </div>
    @endcan

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($produks as $produk)
        <div class="col">
            <div class="card shadow-sm">
               <img src="{{ asset('images/products/'.$produk->image) }}" class="card-img-top" alt="{{ $produk->nama }}" onerror="this.src='{{ asset('images/default.png') }}'">

                <div class="card-body">
                    <h5 class="card-title">{{ $produk->nama }}</h5>
                    <p class="card-text">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <button class="btn btn-primary add-to-cart" data-id="{{ $produk->id }}">
                        <i class="fa fa-cart-plus"></i> Beli
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Belum ada produk yang tersedia.
            </div>
        </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cartCount = document.getElementById('cart-count');

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            const produkId = this.dataset.id;

            fetch("{{ route('keranjang.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: produkId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartCount.innerText = data.totalItems;
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Produk telah ditambahkan ke keranjang.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire("Gagal", "Terjadi kesalahan saat menambahkan ke keranjang.", "error");
                }
            })
            .catch(() => {
                Swal.fire("Error", "Terjadi kesalahan jaringan.", "error");
            });
        });
    });
});
</script>
@endsection
