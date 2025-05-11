@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Produk</h4>
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
                        <a href="{{ route('pesan.index') }}" class="btn btn-info">
                            <i class="fa fa-envelope"></i> Lihat Pesan Masuk
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Alert Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Live Search --}}
                    <div class="mb-3 position-relative">
                        <form action="{{ route('produk.index') }}" method="GET" id="search-form">
                            <div class="row g-2">
                                <div class="col-md-8 position-relative">
                                    <input type="text" id="live-search" name="search" class="form-control" autocomplete="off" placeholder="Cari produk atau kategori..." value="{{ request('search') }}">
                                    <div id="live-search-results" class="list-group position-absolute w-100 shadow-sm z-3"></div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Tambah Produk --}}
                    @can('create', App\Models\Produk::class)
                        <div class="mb-3">
                            <a href="{{ route('produk.create') }}" class="btn btn-success">
                                <i class="fa fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                    @endcan

                    {{-- Tabel Produk --}}
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $produk)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/images_produk/' . ($produk->image ?? 'default.png')) }}" width="80" class="rounded-3 mb-2">
                                        </td>
                                        <td>{{ $produk->kode_produk }}</td>
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ $produk->kategori->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td>
                                            @can('update', $produk)
                                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning me-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('delete', $produk)
                                                <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger me-1">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan

                                            {{-- Menampilkan tombol "Beli" hanya jika produk ada stoknya dan user login --}}
                                            @if(auth()->check() && $produk->stok > 0)
                                                <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $produk->id }}">
                                                    <i class="fa fa-cart-plus"></i> Beli
                                                </button>
                                            @elseif($produk->stok == 0)
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Stok Habis
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Login untuk membeli
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Tidak ada produk tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="cart-message" class="alert alert-success mt-3 d-none">
                        <i class="fa fa-check-circle"></i> Produk berhasil ditambahkan ke keranjang!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Add to cart
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function () {
            let produkId = this.getAttribute('data-id');
            let originalButtonContent = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Loading...';

            try {
                const response = await fetch("{{ route('keranjang.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ id: produkId, quantity: 1 })
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById('cart-count').innerText = data.totalItems;
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
            } catch (error) {
                Swal.fire("Error", "Gagal menambahkan ke keranjang.", "error");
            } finally {
                this.disabled = false;
                this.innerHTML = originalButtonContent;
            }
        });
    });

    // Live Search AJAX
    const searchInput = document.getElementById('live-search');
    const resultBox = document.getElementById('live-search-results');
    let timeout = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const query = this.value.trim();

        if (!query) {
            resultBox.innerHTML = '';
            return;
        }

        resultBox.innerHTML = '<div class="list-group-item text-muted">Memuat hasil...</div>';

        timeout = setTimeout(() => {
            fetch(`/produk/live-search?search=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(html => resultBox.innerHTML = html)
                .catch(() => {
                    resultBox.innerHTML = '<div class="list-group-item text-danger">Gagal memuat hasil.</div>';
                });
        }, 300);
    });

    resultBox.addEventListener('click', function (e) {
        const item = e.target.closest('.live-search-item');
        if (item) {
            searchInput.value = item.dataset.value;
            resultBox.innerHTML = '';
            document.getElementById('search-form').submit();
        }
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.innerHTML = '';
        }
    });
});
</script>

<style>
#live-search-results {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    z-index: 999;
    max-height: 300px;
    overflow-y: auto;
}

.live-search-item:hover {
    background-color: #f0f0f0;
    cursor: pointer;
}
</style>

@endsection
