@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Game Store</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .btn-custom {
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #0d6efd;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }
        .modal-body iframe {
            width: 100%;
            height: 70vh;
            border: none;
        }
        .kategori-badge {
            font-size: 0.75rem;
            background: #e1eaff;
            color: #0d6efd;
            padding: 5px 10px;
            border-radius: 12px;
        }
        .product-price {
            font-weight: 600;
            font-size: 1.2rem;
            color: #2c3e50;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 fw-bold">🕹️ Game Store</h2>

    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-4 btn-sm">
        <i class="fa fa-arrow-left me-1"></i> Kembali
    </a>

    <form method="GET" action="{{ route('game.store') }}" class="mb-5">
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="🔍 Cari game..." />
            </div>
            <div class="col-md-4">
                <select name="kategori_id" class="form-select">
                    <option value="">📁 Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 btn-custom">
                    Cari
                </button>
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
                        <p class="text-muted small mb-2">{{ $produk->deskripsi ?? '-' }}</p>
                        <span class="kategori-badge mb-2">{{ $produk->kategori->nama }}</span>
                        <p class="small text-muted">Dibuat oleh: <strong>{{ $produk->user->name ?? 'Admin' }}</strong></p>
                        <p class="product-price mt-auto">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                        @if (auth()->check() && auth()->id() === $produk->user_id)
                            <div class="alert alert-warning text-center mt-3">
                                Ini produk yang kamu upload sendiri
                            </div>
                        @else
                            <button type="button" class="btn btn-success w-100 mt-2 btn-custom beli-btn" data-id="{{ $produk->id }}">
                                <i class="fa fa-cart-plus me-1"></i> Beli
                            </button>
                        @endif

                        <button type="button" class="btn btn-outline-primary w-100 mt-2 btn-custom play-btn" data-id="{{ $produk->id }}">
                            <i class="fa fa-play me-1"></i> Coba Game
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
            <p class="text-center fs-5 mt-5">🚫 Tidak ada game ditemukan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $produks->links() }}
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Play button
    document.querySelectorAll('.play-btn').forEach(button => {
        button.addEventListener('click', function () {
            const produkId = this.dataset.id;
            const modalElement = document.getElementById('playModal-' + produkId);
            const modal = new bootstrap.Modal(modalElement);
            const iframe = modalElement.querySelector('iframe');
            const closeBtn = modalElement.querySelector('.close-btn');

            iframe.src = '';
            fetch(`/pesan/play-game/${produkId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        iframe.src = data.url;
                        modal.show();

                        let timerInterval;
                        Swal.fire({
                            title: 'Waktu bermain terbatas!',
                            html: 'Menutup dalam <b></b> detik.',
                            timer: 20000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const b = Swal.getHtmlContainer().querySelector('b');
                                timerInterval = setInterval(() => {
                                    b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        });

                        setTimeout(() => {
                            modal.hide();
                            iframe.src = '';
                        }, 20000);

                        closeBtn.addEventListener('click', () => {
                            iframe.src = '';
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Gagal memuat game.', 'error');
                });
        });
    });

    // Beli button
    document.querySelectorAll('.beli-btn').forEach(button => {
        button.addEventListener('click', function () {
            const produkId = this.dataset.id;

            fetch("{{ route('keranjang.store') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: produkId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Produk ditambahkan ke keranjang.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', 'Tidak bisa menambahkan ke keranjang.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan saat menambahkan ke keranjang.', 'error');
            });
        });
    });
});
</script>
</body>
</html>
