@php use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;@endphp


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h2>Transaction Receipt</h2>
    <p><strong>Transactions ID:</strong> {{ $transactions->id }}</p>
    <p><strong>Status:</strong> {{ $transactions->status }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($transactions->total_harga, 0, ',', '.') }}</p>

    <h4>Items</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($transactions->items, true) as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
