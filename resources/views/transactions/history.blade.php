@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📜 Riwayat Transaksi</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fa fa-home me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    @if($transactions->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle me-2"></i> Belum ada transaksi yang tercatat.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->name ?? 'Tidak diketahui' }}</td>
                        <td>{{ $transaction->created_at->format('d M Y') }}</td>
                        <td>
                            @php
                                $badge = match($transaction->status) {
                                    'success' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }} text-capitalize px-3 py-2">{{ $transaction->status }}</span>
                        </td>
                        <td>Rp{{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="dropdown">
                                    <button class="btn btn-outline-dark dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ubah Status
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($transaction->status === 'pending')
                                            <li>
                                                <button class="dropdown-item text-success" type="submit" name="status" value="success">
                                                    ✔️ Tandai Sukses
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" type="submit" name="status" value="cancelled">
                                                    ❌ Batalkan
                                                </button>
                                            </li>
                                        @elseif($transaction->status === 'success')
                                            <li>
                                                <button class="dropdown-item text-danger" type="submit" name="status" value="cancelled">
                                                    ❌ Ubah ke Batal
                                                </button>
                                            </li>
                                        @else
                                            <li>
                                                <span class="dropdown-item text-muted">Status tidak dapat diubah</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
