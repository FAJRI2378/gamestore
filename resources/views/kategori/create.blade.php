@php use Illuminate\Support\Facades\Route; @endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Tambah Kategori</h4>
                </div>
                <div class="card-body">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf

                        {{-- Nama Kategori --}}
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Simpan Kategori
                        </button>

                        {{-- Tombol Kembali --}}
                        <a href="{{ route('admin.home') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
