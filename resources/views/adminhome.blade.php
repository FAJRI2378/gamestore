@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dashboard Produk</h4>
                    <div>
                        <a href="{{ route('kategori.create') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-plus"></i> Tambah Kategori
                        </a>
                        <a href="{{ route('transactions.history') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-history"></i> Riwayat Transaksi
                        </a>
                        <a href="{{ route('produk.create') }}" class="btn btn-light text-primary fw-bold">
                            + Tambah Produk
                        </a>
                    </div>                    
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
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
                                        <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
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
                        <table class="table table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Kode Produk</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produks as $produk)
                                <tr>
                                    <td>
                                        @if($produk->image)
                                            <img src="{{ asset('images/products/' . $produk->image) }}" alt="{{ $produk->nama }}" class="img-fluid" style="width: 100px; height: auto;">
                                        @else
                                            <span class="text-muted">Tidak ada gambar</span>
                                        @endif
                                    </td>
                                    <td>{{ $produk->kode_produk }}</td>
                                    <td>{{ $produk->nama }}</td>
                                    <td>{{ $produk->kategori->nama ?? '-' }}</td>
                                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="d-inline" id="delete-form-{{ $produk->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $produk->id }})">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada produk tersedia.</td>
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
    function confirmDelete(id) {
        Swal.fire({
            title: "Hapus Produk?",
            text: "Produk yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
