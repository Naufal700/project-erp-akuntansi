<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Piutang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Laporan Piutang</h2>
    <table>
        <thead>
            <tr>
                <th>Nomor Invoice</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Jatuh Tempo</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->nomor_invoice }}</td>
                    <td>{{ $item->customer->nama ?? '-' }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jatuh_tempo }}</td>
                    <td>{{ number_format($item->total + $item->ppn, 2, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
