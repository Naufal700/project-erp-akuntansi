<!DOCTYPE html>
<html>

<head>
    <title>Buku Besar PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h2 {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <h1>Laporan Buku Besar</h1>

    @foreach ($data as $item)
        <h2>{{ $item['coa']->kode_akun }} - {{ $item['coa']->nama_akun }}</h2>

        <p>Saldo Awal: {{ number_format($item['saldo_awal'], 2) }}</p>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item['jurnal'] as $jurnal)
                    <tr>
                        <td>{{ $jurnal->tanggal }}</td>
                        <td>{{ $jurnal->keterangan }}</td>
                        <td>{{ number_format($jurnal->nominal_debit, 2) }}</td>
                        <td>{{ number_format($jurnal->nominal_kredit, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Total Debit: {{ number_format($item['total_debit'], 2) }}</p>
        <p>Total Kredit: {{ number_format($item['total_kredit'], 2) }}</p>
        <p><strong>Saldo Akhir: {{ number_format($item['saldo_akhir'], 2) }}</strong></p>
        <hr>
    @endforeach

</body>

</html>
