<!DOCTYPE html>
<html>

<head>
    <title>Detail Pembayaran Penjualan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Detail Pembayaran Penjualan</h2>

    <table>
        <tr>
            <th>Nomor Invoice</th>
            <td>{{ $pembayaran->invoice->nomor_invoice }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Metode Pembayaran</th>
            <td>{{ $pembayaran->metodePembayaran->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td>Rp {{ number_format($pembayaran->jumlah, 2, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
