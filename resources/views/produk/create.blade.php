@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Game</h4>
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
                            <input type="text" name="kode_produk" class="form-control @error('kode_produk') is-invalid @enderror" value="{{ old('kode_produk') }}" required>
                            @error('kode_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama Produk --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Game</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Produk --}}
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" required min="0">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori Produk --}}
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok Produk --}}
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}" required min="0">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload File Game (ZIP) --}}
                        <div class="mb-3">
                            <label for="game" class="form-label">File Game (ZIP)</label>
                            <input type="file" name="game" class="form-control @error('game') is-invalid @enderror" accept=".zip">
                            @error('game')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload Gambar Thumbnail --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Thumbnail Game (Gambar)</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Game
                        </button>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection