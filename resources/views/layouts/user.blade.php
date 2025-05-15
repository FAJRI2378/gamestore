<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Produk</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-pb7TsCpihIyLM2cGl3GbGpTZ6V0qRwkgH1Y9kBvMdVk/tCWwqX0zy+mcdD1cXz00Uj+XDGz0zw9SCp07wJ37SQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>
<div class="container mt-4">
    <h4>Dashboard Produk</h4>
    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th style="width: 160px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Contoh data dummy
                $produks = [
                    (object)[ 'id'=>1, 'nama'=>'Produk A', 'harga'=>150000, 'stok'=>10 ],
                    (object)[ 'id'=>2, 'nama'=>'Produk B', 'harga'=>200000, 'stok'=>5 ],
                ];
            @endphp

            @foreach($produks as $produk)
                <tr>
                    <td>{{ $produk->nama }}</td>
                    <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td>{{ $produk->stok }}</td>
                    <td>
                        <a href="{{ url('/produk/'.$produk->id.'/edit') }}" class="btn btn-sm btn-warning me-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" title="Hapus" onclick="alert('Hapus produk {{ $produk->nama }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
