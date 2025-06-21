<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }

        .sub-title {
            font-size: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            border-bottom: 1px solid #ccc;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .category-title {
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 4px;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .total-final {
            font-size: 13px;
            font-weight: bold;
            border-top: 2px solid #000;
            margin-top: 10px;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="title">LAPORAN POSISI KEUANGAN (NERACA)</div>
    <div class="sub-title">
        Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}
    </div>

    <table>
        <tr>
            <!-- Kolom Aset -->
            <td style="width: 50%; vertical-align: top; padding-right: 10px;">
                <div class="section-title">ASET</div>

                <div class="category-title">Aset Lancar</div>
                <table>
                    @foreach ($aset_lancar as $item)
                        <tr>
                            <td>{{ $item['nama_akun'] }}</td>
                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Subtotal Aset Lancar</td>
                        <td class="text-end">Rp {{ number_format(abs($sub_aset_lancar), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="category-title">Aset Tetap</div>
                <table>
                    @foreach ($aset_tetap as $item)
                        <tr>
                            <td>{{ $item['nama_akun'] }}</td>
                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Subtotal Aset Tetap</td>
                        <td class="text-end">Rp {{ number_format(abs($sub_aset_tetap), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="total-final text-end">
                    Total Aset: Rp {{ number_format(abs($total_aset), 2, ',', '.') }}
                </div>
            </td>

            <!-- Kolom Kewajiban dan Ekuitas -->
            <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                <div class="section-title">KEWAJIBAN & EKUITAS</div>

                <div class="category-title">Kewajiban Jangka Pendek</div>
                <table>
                    @foreach ($kewajiban_jp as $item)
                        <tr>
                            <td>{{ $item['nama_akun'] }}</td>
                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Subtotal Kewajiban Jangka Pendek</td>
                        <td class="text-end">Rp {{ number_format(abs($sub_kewajiban_jp), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="category-title">Kewajiban Jangka Panjang</div>
                <table>
                    @foreach ($kewajiban_pj as $item)
                        <tr>
                            <td>{{ $item['nama_akun'] }}</td>
                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Subtotal Kewajiban Jangka Panjang</td>
                        <td class="text-end">Rp {{ number_format(abs($sub_kewajiban_pj), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="category-title">Ekuitas</div>
                <table>
                    @foreach ($modal as $item)
                        <tr>
                            <td>{{ $item['nama_akun'] }}</td>
                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Laba Ditahan Tahun Lalu</td>
                        <td class="text-end">Rp {{ number_format(abs($laba_ditahan ?? 0), 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba Bersih Berjalan</td>
                        <td class="text-end">Rp {{ number_format(abs($laba_berjalan ?? 0), 2, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Subtotal Ekuitas</td>
                        <td class="text-end">Rp {{ number_format(abs($total_modal), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="total-final text-end">
                    Total Kewajiban & Ekuitas: Rp {{ number_format(abs($total_passiva), 2, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
