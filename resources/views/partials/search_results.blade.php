{{-- resources/views/partials/search_results.blade.php --}}
@forelse($produks as $produk)
    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
        <img src="{{ $produk->image ? asset('storage/images_produk/' . $produk->image) : asset('storage/images_produk/default.png') }}"
             alt="Gambar" width="50" height="50" class="rounded">
        <div class="flex-grow-1">
            <div class="fw-semibold">{{ $produk->nama }}</div>
            <small class="text-muted">Rp {{ number_format($produk->harga, 0, ',', '.') }}</small>
        </div>
        <span class="badge bg-secondary">{{ $produk->kategori->nama ?? '-' }}</span>
    </a>
@empty
    <div class="list-group-item text-muted">Tidak ditemukan</div>
@endforelse
