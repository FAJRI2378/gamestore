@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pesan dari Admin</h3>
    @forelse($pesans as $pesan)
        <div class="card mb-3 {{ $pesan->dibaca ? '' : 'border-warning' }}">
            <div class="card-body">
                <p>{{ $pesan->isi }}</p>
                <small class="text-muted">Dikirim: {{ $pesan->created_at->diffForHumans() }}</small>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada pesan.</div>
    @endforelse
</div>
@endsection
