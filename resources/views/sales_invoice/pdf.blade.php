<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Faktur Penjualan {{ $invoice->nomor_invoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .company-info {
            float: left;
            width: 60%;
        }

        .company-logo {
            float: right;
            width: 35%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        h1 {
            font-size: 18px;
            margin: 0;
            padding-bottom: 5px;
        }

        .invoice-info,
        .customer-info {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background-color: #eee;
            text-align: center;
        }

        td.text-right {
            text-align: right;
        }

        .totals td {
            border: none;
            padding: 3px 6px;
        }

        .totals tr.total-row td {
            border-top: 1px solid #000;
            font-weight: bold;
        }

        .terbilang {
            font-style: italic;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #000;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    <header>
        <div class="company-info">
            <h1>PT. Contoh Perusahaan</h1>
            <div>Jl. Contoh Alamat No. 123, Jakarta</div>
            <div>NPWP: 01.234.567.8-999.000</div>
            <div>Telp: (021) 12345678</div>
            <div>Email: info@contohperusahaan.co.id</div>
        </div>
        <div class="company-logo">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo Perusahaan" height="60">
        </div>
        <div class="clear"></div>
    </header>

    <div class="invoice-info">
        <table>
            <tr>
                <td><strong>Nomor Faktur</strong></td>
                <td>{{ $invoice->nomor_invoice }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Jatuh Tempo</strong></td>
                <td>{{ $invoice->jatuh_tempo ? \Carbon\Carbon::parse($invoice->jatuh_tempo)->format('d-m-Y') : '-' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="customer-info">
        <table>
            <tr>
                <td><strong>Nama Pelanggan</strong></td>
                <td>{{ $invoice->salesOrder->customer->nama }}</td>
            </tr>
            <tr>
                <td><strong>Alamat</strong></td>
                <td>{{ $invoice->salesOrder->customer->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>NPWP</strong></td>
                <td>{{ $invoice->salesOrder->customer->npwp ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:50%;">Nama Produk</th>
                <th style="width:10%;">Qty</th>
                <th style="width:15%;">Harga Satuan (Rp)</th>
                <th style="width:10%;">Diskon (Rp)</th>
                <th style="width:15%;">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
                $totalDiskon = 0;
            @endphp
            @foreach ($invoice->salesOrder->details as $index => $detail)
                @php
                    $hargaSatuan = $detail->harga ?? 0;
                    $qty = $detail->qty ?? 0;
                    $diskon = $detail->diskon ?? 0;
                    $itemSubtotal = $hargaSatuan * $qty - $diskon;

                    $subtotal += $hargaSatuan * $qty;
                    $totalDiskon += $diskon;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-right">{{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($diskon, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($itemSubtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        @php
            $ppnPersen = 11;
            $ppn = (($subtotal - $totalDiskon) * $ppnPersen) / 100;
            $grandTotal = $subtotal - $totalDiskon + $ppn;

            function terbilang($angka)
            {
                $angka = (int) $angka;
                $abil = [
                    '',
                    'Satu',
                    'Dua',
                    'Tiga',
                    'Empat',
                    'Lima',
                    'Enam',
                    'Tujuh',
                    'Delapan',
                    'Sembilan',
                    'Sepuluh',
                    'Sebelas',
                ];
                if ($angka < 12) {
                    return ' ' . $abil[$angka];
                } elseif ($angka < 20) {
                    return terbilang($angka - 10) . ' Belas';
                } elseif ($angka < 100) {
                    return terbilang($angka / 10) . ' Puluh' . terbilang($angka % 10);
                } elseif ($angka < 200) {
                    return ' Seratus' . terbilang($angka - 100);
                } elseif ($angka < 1000) {
                    return terbilang($angka / 100) . ' Ratus' . terbilang($angka % 100);
                } elseif ($angka < 2000) {
                    return ' Seribu' . terbilang($angka - 1000);
                } elseif ($angka < 1000000) {
                    return terbilang($angka / 1000) . ' Ribu' . terbilang($angka % 1000);
                } elseif ($angka < 1000000000) {
                    return terbilang($angka / 1000000) . ' Juta' . terbilang($angka % 1000000);
                } else {
                    return 'Angka terlalu besar';
                }
            }
        @endphp
        <tfoot class="totals">
            <tr>
                <td colspan="5" class="text-end">Subtotal</td>
                <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">Total Diskon</td>
                <td class="text-right">{{ number_format($totalDiskon, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">PPN ({{ $ppnPersen }}%)</td>
                <td class="text-right">{{ number_format($ppn, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-end">Grand Total</td>
                <td class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="terbilang">
        Terbilang: <strong>{{ trim(terbilang($grandTotal)) }} Rupiah</strong>
    </div>

    <div style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; text-align: center;">
                    <strong>Hormat Kami,</strong><br><br
