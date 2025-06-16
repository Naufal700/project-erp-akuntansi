<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Produk</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: left
        }
    </style>
</head>

<body>
    <h3>Data Produk</h3>
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Satuan</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk as $row)
                <tr>
                    <td>{{ $row->kode_produk }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->satuan }}</td>
                    <td>{{ number_format($row->harga_beli, 2) }}</td>
                    <td>{{ number_format($row->harga_jual, 2) }}</td>
                    <td>{{ $row->stok }}</td>
                    <td>{{ $row->tipe_produk }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
