@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                    <h4 class="mb-0">
                        <i class="fa fa-boxes me-2"></i> Dashboard Produk
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('transactions.history') }}" class="btn btn-outline-light">
                            <i class="fa fa-history me-1"></i> Riwayat Transaksi
                        </a>
                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-light">
                            <i class="fa fa-list me-1"></i> Kelola Kategori
                        </a>
                        <a href="{{ route('produk.create') }}" class="btn btn-light text-primary fw-bold">
                            <i class="fa fa-plus-circle"></i> Tambah Produk
                        </a>
                    </div>                    
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Filter --}}
                    <div class="mb-4">
                        <form action="{{ route('produk.index') }}" method="GET" class="row g-2">
                            <div class="col-md-4">
                                <select class="form-select" name="kategori_id" onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari Produk..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </form>
                    </div>

                    {{-- Tabel Produk --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Kode Produk</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th style="width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $produk)
                                    <tr>
                                        <td>
                                            @if($produk->image)
                                                <img src="{{ asset('storage/images_produk/' . $produk->image) }}" alt="Gambar" width="80" class="rounded-3">
                                            @else
                                                <img src="{{ asset('storage/images_produk/default.png') }}" alt="Default Gambar" width="80" class="rounded-3">
                                            @endif
                                            <div>{{ $produk->nama }}</div>
                                        </td>
                                        <td>{{ $produk->kode_produk }}</td>
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ $produk->kategori->nama ?? 'Tidak ada' }}</td>
                                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td>
                                            <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning me-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $produk->id }}, '{{ $produk->nama }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $produk->id }}" action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted text-center">Belum ada produk yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $produks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, namaProduk) {
        Swal.fire({
            title: `Hapus produk "${namaProduk}"?`,
            text: "Produk yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
    @endif
</script>
@endsection
