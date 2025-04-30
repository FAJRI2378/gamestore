@php use Illuminate\Support\Facades\Route; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">Edit Produk</h4>
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

                    <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Kode Produk --}}
                        <div class="mb-3">
                            <label for="kode_produk" class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" value="{{ $produk->kode_produk }}" required>
                        </div>

                        {{-- Nama Produk --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
                        </div>

                        {{-- Harga Produk --}}
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required min="0">
                        </div>

                        {{-- Kategori Produk --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $produk->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Gambar Produk --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Produk</label>

                            {{-- Menampilkan gambar produk yang ada --}}
                            @if($produk->image)
                                <div>
                                    <img id="preview-image" src="{{ asset('storage/public/images_produk/' . $produk->image) }}" alt="Gambar Produk" width="150" class="img-thumbnail">
                                </div>
                            @else
                                <div>Gambar tidak tersedia.</div>
                            @endif

                            {{-- Input untuk mengganti gambar --}}
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.home') }}" class="btn btn-secondary">Kembali</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk pratinjau gambar sebelum diunggah
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('preview-image');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
