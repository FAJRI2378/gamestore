@php use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;@endphp


@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Transaction History</h2>

    @foreach ($transactions as $trx)
        <div class="card my-3">
            <div class="card-body">
                <p><strong>Receipt No:</strong> {{ $trx->resi }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($trx->status) }}</p>
                <p><strong>Date:</strong> {{ $trx->created_at->format('d M Y, H:i') }}</p>

                <a href="{{ route('transactions.print', $trx->id) }}" class="btn btn-sm btn-primary">Print Receipt</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
