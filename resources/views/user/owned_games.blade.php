@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">🎮 Game yang Kamu Miliki</h3>

    @if($cancelled)
        <div class="alert alert-warning text-center">
            ⚠️ Salah satu transaksi kamu telah digagalkan oleh admin. <br>
            <a href="{{ route('pesan.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                Cek pesan dari admin
            </a>
        </div>
    @endif

    @if($ownedGames->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle me-2"></i> Kamu belum membeli game apapun atau pembayaran belum disetujui.
        </div>
    @else
        <div class="row">
            @foreach($ownedGames as $produk)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                       
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $produk->nama }}</h5>
                            <p class="card-text">{{ $produk->deskripsi ?? '-' }}</p>
                            <p class="fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

                            <button type="button" class="btn btn-primary mt-auto play-btn" data-id="{{ $produk->id }}">
                                <i class="fa fa-play"></i> Mainkan Game
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <iframe src="" frameborder="0" allowfullscreen style="width: 100%; height: 100%;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const playButtons = document.querySelectorAll('.play-btn');

    playButtons.forEach(button => {
        button.addEventListener('click', function () {
            const produkId = this.getAttribute('data-id');
            const modalElement = document.getElementById('playModal-' + produkId);
            const iframe = modalElement.querySelector('iframe');

            // Buat instance modal bootstrap
            const modal = new bootstrap.Modal(modalElement);

            // Reset iframe src dulu agar loading ulang
            iframe.src = '';

            fetch(`/pesan/play-game/${produkId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        iframe.src = data.url;
                        modal.show();

                        Swal.fire({
                            title: 'Selamat!',
                            text: 'Kamu memiliki akses tanpa batas waktu untuk bermain game ini.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire('Error', 'Gagal memuat game.', 'error');
                });

            // Bersihkan iframe src saat modal tertutup untuk hentikan game
            modalElement.addEventListener('hidden.bs.modal', function () {
                iframe.src = '';
            }, { once: true });
        });
    });
});
</script>
@endsection
