@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pesan yang Dikirim oleh Admin</h3>

    @forelse($pesans as $pesan)
        <div class="card mb-3 {{ $pesan->dibaca ? '' : 'border-warning' }}">
            <div class="card-body">
                <p><strong>Dari:</strong> {{ $pesan->sender->name }} ({{ $pesan->sender->email }})</p>
                <p>{{ $pesan->isi }}</p>
                <small class="text-muted">Dikirim: {{ $pesan->created_at->diffForHumans() }}</small>
                <hr>
                <small class="text-muted">
                    <strong>Penerima:</strong> {{ $pesan->recipient->name }} ({{ $pesan->recipient->email }})
                </small>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada pesan.</div>
    @endforelse
</div>
@endsection
