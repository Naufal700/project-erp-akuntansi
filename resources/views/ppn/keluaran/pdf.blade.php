<!DOCTYPE html>
<html>

<head>
    <title>PPN Keluaran</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        td.text-left {
            text-align: left;
        }

        td.text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Daftar PPN Keluaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nomor Faktur</th>
                <th>Customer</th>
                <th>DPP</th>
                <th>PPN (11%)</th>
                <th>Total Faktur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td class="text-left">{{ $item['nomor_faktur'] }}</td>
                    <td class="text-left">{{ $item['customer'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['dpp'], 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['ppn'], 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['total'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
