<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store</title>

    <!-- FontAwesome dan Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Game Store</h2>

        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>

        <form method="GET" action="{{ route('game.store') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari game...">
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>
        </form>

        <div class="row">
            @forelse ($produks as $produk)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if ($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" class="card-img-top" alt="{{ $produk->nama }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $produk->nama }}</h5>
                            <p class="card-text">{{ $produk->deskripsi }}</p>
                            <p class="text-muted mb-1">Kategori: {{ $produk->kategori->nama }}</p>
                            <p class="text-muted mb-1">Dibuat oleh: {{ $produk->user->name ?? 'Admin' }}</p>
                            <p class="fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                            @if (auth()->check() && auth()->id() === $produk->user_id)
                                <div class="alert alert-warning text-center mt-auto mb-0">
                                    Ini produk yang kamu upload sendiri
                                </div>
                            @else
                                <form action="{{ route('keranjang.store') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                    <button type="submit" class="btn btn-success w-100">Beli</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Tidak ada game ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $produks->links() }}
        </div>
    </div>

    <!-- Bootstrap JS CDN (opsional jika butuh interaktif tambahan) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
