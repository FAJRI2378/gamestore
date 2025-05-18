@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Game Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .modal-body iframe {
            width: 100%;
            height: 70vh;
            border: none;
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
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari game..." />
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
            @php
                $gameExists = $produk->game && Storage::disk('public')->exists('games_produk/' . $produk->game);
            @endphp

            @if (!$gameExists)
                @continue
            @endif

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if ($produk->image)
                        <img src="{{ asset('storage/images_produk/' . $produk->image) }}" class="card-img-top" alt="{{ $produk->nama }}" />
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $produk->nama }}</h5>
                        <p class="card-text">{{ $produk->deskripsi ?? '-' }}</p>
                        <p class="text-muted mb-1">Kategori: {{ $produk->kategori->nama }}</p>
                        <p class="text-muted mb-1">Dibuat oleh: {{ $produk->user->name ?? 'Admin' }}</p>
                        <p class="fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                        @if (auth()->check() && auth()->id() === $produk->user_id)
                            <div class="alert alert-warning text-center mt-auto mb-2">
                                Ini produk yang kamu upload sendiri
                            </div>
                        @else
                            <form action="{{ route('keranjang.store') }}" method="POST" class="mt-auto mb-2">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <button type="submit" class="btn btn-success w-100">Beli</button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-primary w-100 play-btn" data-id="{{ $produk->id }}" data-file="{{ $produk->game }}">
                            <i class="fa fa-play"></i> Play 20 detik
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal fade" id="playModal-{{ $produk->id }}" tabindex="-1" aria-labelledby="playModalLabel-{{ $produk->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 90vw;">
                    <div class="modal-content" style="height: 80vh;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="playModalLabel-{{ $produk->id }}">{{ $produk->nama }}</h5>
                            <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Tidak ada game ditemukan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $produks->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const playButtons = document.querySelectorAll('.play-btn');

        playButtons.forEach(button => {
            button.addEventListener('click', function () {
                const produkId = this.getAttribute('data-id');
                const gameFile = this.getAttribute('data-file');
                const modal = new bootstrap.Modal(document.getElementById('playModal-' + produkId));
                const iframe = document.querySelector('#playModal-' + produkId + ' iframe');
                const gameUrl = "{{ url('storage/games_produk') }}/" + gameFile + "/index.html";

                iframe.src = gameUrl;
                modal.show();

                // Sembunyikan modal dan kosongkan iframe setelah 20 detik
                setTimeout(() => {
                    modal.hide();
                    iframe.src = '';
                }, 20000);

                const closeBtn = document.querySelector('#playModal-' + produkId + ' .close-btn');
                closeBtn.addEventListener('click', () => {
                    iframe.src = '';
                });
            });
        });
    });
</script>

</body>
</html>
