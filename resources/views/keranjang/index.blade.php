@php use Illuminate\Support\Facades\Route; @endphp


@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(!empty($cart))
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga/Kg</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $total += $item['harga'] * $item['quantity']; @endphp
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>
                                <input type="number" class="form-control quantity" data-id="{{ $id }}" value="{{ $item['quantity'] }}" min="1">
                            </td>
                            <td>Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-danger remove-item" data-id="{{ $id }}">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h4 class="mt-3">Total: <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></h4>

        <button class="btn btn-warning clear-cart">
            <i class="fa fa-trash"></i> Kosongkan Keranjang
        </button>
        <a href="{{ route('keranjang.checkout') }}" class="btn btn-success checkout-btn" id="btn-checkout">
            <i class="fa fa-credit-card"></i> Checkout
        </a>

        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fa fa-home"></i> Kembali ke Home
        </a>
    @else
        <p class="text-muted">Keranjang belanja kosong.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fa fa-home"></i> Kembali ke Home
        </a>
    @endif
</div>

<!-- SweetAlert & Script AJAX -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
     const totalBelanja = @json($total ?? 0);
document.addEventListener('DOMContentLoaded', function() {
    // Update jumlah produk dalam keranjang
    document.querySelectorAll('.quantity').forEach(input => {
        input.addEventListener('change', function() {
            let id = this.getAttribute('data-id');
            let quantity = this.value;

            fetch("{{ route('keranjang.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id: id, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert("Gagal memperbarui jumlah produk.");
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Konfirmasi sebelum Checkout
    document.getElementById('btn-checkout')?.addEventListener('click', function(event) {
    event.preventDefault();

    Swal.fire({
        title: "Lanjutkan ke Checkout?",
        html: "Total belanja Anda: <strong>Rp " + totalBelanja.toLocaleString('id-ID') + "</strong><br>Pastikan semua produk sudah benar.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Checkout",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = this.href;
        }
    });
});


    // Hapus item dari keranjang dengan SweetAlert
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Produk ini akan dihapus dari keranjang.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('keranjang.remove') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Dihapus!", "Produk telah dihapus.", "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Gagal!", "Produk gagal dihapus.", "error");
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });

    // Kosongkan seluruh keranjang dengan SweetAlert
    document.querySelector('.clear-cart')?.addEventListener('click', function() {
        Swal.fire({
            title: "Yakin ingin mengosongkan keranjang?",
            text: "Semua produk akan dihapus.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, kosongkan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('keranjang.clear') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Dikosongkan!", "Keranjang telah dikosongkan.", "success").then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire("Gagal!", "Gagal mengosongkan keranjang.", "error");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    // Disabled tombol Checkout jika keranjang kosong
    let checkoutButton = document.querySelector('.checkout-btn');
    if (!document.querySelector('.quantity')) {
        checkoutButton.setAttribute('disabled', 'disabled');
    }
});
</script>

@endsection
