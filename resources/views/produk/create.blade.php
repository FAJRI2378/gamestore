@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Produk</h4>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form Tambah Produk --}}
                    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="kode_produk" class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" id="kode_produk" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control" required min="0">
                        </div>

                        {{-- Upload Gambar --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            
                            {{-- Preview Gambar --}}
                            <div class="mt-3" id="image-preview-container">
                                <img id="preview-image" src="#" alt="Preview Gambar" class="img-thumbnail d-none" width="150">
                            </div>
                        </div>

                        {{-- Pilih Kategori --}}
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Simpan Produk
                            </button>
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk pratinjau gambar yang dipilih oleh pengguna
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview-image');
            preview.src = reader.result;
            preview.classList.remove('d-none');  // Menampilkan gambar pratinjau
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
