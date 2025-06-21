<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Besar</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 14px;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h1>Laporan Buku Besar</h1>
    <p class="text-center">Periode: {{ tanggal_indonesia(request('tanggal_awal')) }} s.d.
        {{ tanggal_indonesia(request('tanggal_akhir')) }}</p>

    @foreach ($data as $item)
        <h2>{{ $item['coa']->kode_akun }} - {{ $item['coa']->nama_akun }}</h2>

        {{-- Saldo Awal --}}
        <table>
            <tr>
                <th colspan="2">Saldo Awal</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Kredit</th>
                <th class="text-right">Saldo</th>
            </tr>
            @php
                $debit_awal = $item['coa']->saldo_awal_debit ?? 0;
                $kredit_awal = $item['coa']->saldo_awal_kredit ?? 0;
                $saldo_running = $debit_awal - $kredit_awal;
            @endphp
            <tr>
                <td colspan="2">Saldo Awal per {{ tanggal_indonesia(request('tanggal_awal')) }}</td>
                <td class="text-right">{{ number_format($debit_awal, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($kredit_awal, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($saldo_running, 2, ',', '.') }}</td>
            </tr>
        </table>

        {{-- Tabel Transaksi --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Tanggal</th>
                    <th style="width: 200px;">Keterangan</th>
                    <th class="text-right" style="width: 100px;">Debit</th>
                    <th class="text-right" style="width: 100px;">Kredit</th>
                    <th class="text-right" style="width: 100px;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($item['jurnal'] as $jurnal)
                    @php
                        $saldo_running += $jurnal->nominal_debit - $jurnal->nominal_kredit;
                    @endphp
                    <tr>
                        <td>{{ tanggal_indonesia($jurnal->tanggal) }}</td>
                        <td>{{ $jurnal->keterangan }}</td>
                        <td class="text-right">{{ number_format($jurnal->nominal_debit, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($jurnal->nominal_kredit, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($saldo_running, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Ringkasan --}}
        <div class="summary">
            <p><strong>Total Debit:</strong> {{ number_format($item['total_debit'], 2, ',', '.') }}</p>
            <p><strong>Total Kredit:</strong> {{ number_format($item['total_kredit'], 2, ',', '.') }}</p>
            <p><strong>Saldo Akhir:</strong> {{ number_format($item['saldo_akhir'], 2, ',', '.') }}</p>
        </div>

        <hr style="margin: 30px 0;">
    @endforeach

</body>

</html>
