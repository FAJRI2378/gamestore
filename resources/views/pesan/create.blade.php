@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kirim Pesan ke User</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pesan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="to_id">Pilih User</label>
            <select name="to_id" class="form-control">
                <option value="">Pilih Pengguna</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('to_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('to_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="isi">Isi Pesan</label>
            <textarea name="isi" class="form-control" rows="4">{{ old('isi') }}</textarea>
            @error('isi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection
