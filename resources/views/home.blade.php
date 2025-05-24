@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-6">
            <h3 class="fw-bold">🛒 Daftar Produk</h3>
        </div>
        <div class="col-md-6 text-end d-flex justify-content-end gap-2 flex-wrap">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-history me-1"></i> Riwayat
            </a>
            <a href="{{ route('keranjang.index') }}" class="btn btn-warning position-relative">
                <i class="fa fa-shopping-cart me-1"></i> Keranjang
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                    {{ count((array) session('cart')) }}
                </span>
            </a>
            <a href="{{ route('pesan.index') }}" class="btn btn-info">
                <i class="fa fa-envelope me-1"></i> Pesan Masuk
            </a>
            <a href="{{ route('game.store') }}" class="btn btn-success">
                <i class="fa fa-gamepad me-1"></i> Game Store
            </a>
            <a href="{{ route('pesan.user.owned_games') }}" class="btn btn-outline-primary">
                🎮 Game Kamu
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Form Pencarian --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('produk.index') }}" method="GET" id="search-form">
                <div class="row g-2 align-items-center">
                    <div class="col-md-9 position-relative">
                        <input type="text" id="live-search" name="search" class="form-control" placeholder="🔍 Cari produk atau kategori..." value="{{ request('search') }}">
                        <div id="live-search-results" class="list-group position-absolute w-100 shadow-sm z-3"></div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100"><i class="fa fa-search me-1"></i> Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tombol Tambah Produk --}}
    @if(auth()->check())
    <div class="mb-3 text-end">
        <a href="{{ route('produk.create') }}" class="btn btn-success">
            <i class="fa fa-plus me-1"></i> Tambah Produk
        </a>
    </div>
    @endif

    {{-- Tabel Produk --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama</th>
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
                            <img src="{{ asset('storage/images_produk/' . ($produk->image ?? 'default.png')) }}" alt="{{ $produk->nama }}" width="80" class="rounded-3 shadow-sm">
                        </td>
                        <td>{{ $produk->kode_produk }}</td>
                        <td>{{ $produk->nama }}</td>
                        <td>{{ $produk->kategori->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td>{{ $produk->stok }}</td>
                        <td>
                            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $produk->user_id))
                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-muted">Tidak ada produk tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pesan Keranjang --}}
    <div id="cart-message" class="alert alert-success mt-3 d-none">
        <i class="fa fa-check-circle me-1"></i> Produk berhasil ditambahkan ke keranjang!
    </div>
</div>
@endsection
