@php use Illuminate\Support\Facades\Route; @endphp

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
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Kode Produk --}}
                        <div class="mb-3">
                            <label for="kode_produk" class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" value="{{ old('kode_produk') }}" required>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>

                        {{-- Harga Produk --}}
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required min="0">
                        </div>

                        {{-- Kategori Produk --}}
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Stok Produk --}}
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}" required min="0">
                        </div>

                        {{-- Gambar Produk (Opsional) --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                        </div>

                        {{-- Preview Gambar --}}
                        <div class="mb-3" style="display:none;" id="image-preview-container">
                            <img id="preview-image" src="#" alt="Preview Gambar" width="150" class="img-thumbnail">
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Produk
                        </button>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi pratinjau gambar sebelum diunggah
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview-image');
            const container = document.getElementById('image-preview-container');
            preview.src = reader.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
