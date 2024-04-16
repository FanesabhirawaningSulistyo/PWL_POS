<!DOCTYPE html>
<html>
<head>
    <title>Struk Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h3 {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Struk Penjualan</h2>
        <p>ID: {{ $penjualan->penjualan_id }}</p>
        <p>Username Staff: {{ $penjualan->user->username }}</p>
        <p>Pembeli: {{ $penjualan->pembeli }}</p>
        <p>Kode Penjualan: {{ $penjualan->penjualan_kode }}</p>
        <p>Tanggal Penjualan: {{ $penjualan->penjualan_tanggal }}</p>
        <h3>Barang yang dibeli</h3>
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan->detail as $detail)
                    <tr>
                        <td>{{ $detail->barang->barang_nama }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>{{ $detail->harga }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <th colspan="2">TOTAL</th>
                    <td>{{ $penjualan->detail->sum('harga') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
