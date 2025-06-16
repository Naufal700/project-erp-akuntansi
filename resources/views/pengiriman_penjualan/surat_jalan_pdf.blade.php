<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Surat Jalan - {{ $pengiriman->nomor_surat_jalan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background-color: #eee;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border {
            border: none;
        }

        .ttd-space {
            height: 80px;
        }
    </style>
</head>

<body>
    <h2>SURAT JALAN</h2>

    <table class="no-border" style="margin-bottom: 30px;">
        <tr class="no-border">
            <td class="no-border" style="width: 50%;">
                <strong>Nomor Surat Jalan:</strong><br>
                {{ $pengiriman->nomor_surat_jalan }}
            </td>
            <td class="no-border" style="width: 50%;">
                <strong>Tanggal Pengiriman:</strong><br>
                {{ $pengiriman->tanggal->format('d-m-Y') }}
            </td>
        </tr>
        <tr class="no-border">
            <td class="no-border">
                <strong>Nomor Sales Order:</strong><br>
                {{ $pengiriman->salesOrder->nomor_so ?? '-' }}
            </td>
            <td class="no-border">
                <strong>Nama Pelanggan:</strong><br>
                {{ $pengiriman->salesOrder->pelanggan->nama ?? '-' }}
            </td>
        </tr>
        <tr class="no-border">
            <td class="no-border" colspan="2">
                <strong>Status Pengiriman:</strong><br>
                {{ ucfirst($pengiriman->status_pengiriman) }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Produk</th>
                <th style="width: 15%;">Kuantitas</th>
                <th style="width: 15%;">Satuan</th>
                <th style="width: 15%;">Harga (Rp)</th>
                <th style="width: 15%;">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if (
                $pengiriman->salesOrder &&
                    $pengiriman->salesOrder->salesOrderDetail &&
                    $pengiriman->salesOrder->salesOrderDetail->count())
                @foreach ($pengiriman->salesOrder->salesOrderDetail as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama ?? '-' }}</td>
                        <td class="text-center">{{ $detail->qty }}</td>
                        <td class="text-center">{{ $detail->produk->satuan ?? '-' }}</td>
                        <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">Tidak ada detail pengiriman.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total</th>
                <th class="text-right">
                    {{ number_format(
                        $pengiriman->salesOrder && $pengiriman->salesOrder->salesOrderDetail
                            ? $pengiriman->salesOrder->salesOrderDetail->sum(function ($detail) {
                                return $detail->qty * $detail->harga;
                            })
                            : 0,
                        0,
                        ',',
                        '.',
                    ) }}
                </th>
            </tr>
        </tfoot>
    </table>

    <table style="width: 100%; margin-top: 60px; text-align: center;">
        <tr>
            <td>Pengirim</td>
            <td>Pengantar</td>
            <td>Penerima</td>
        </tr>
        <tr class="ttd-space">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>(__________________)</td>
            <td>(__________________)</td>
            <td>(__________________)</td>
        </tr>
    </table>
</body>

</html>
