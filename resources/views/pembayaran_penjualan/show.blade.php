@extends('adminlte::page')

@section('title', 'Detail Pembayaran Penjualan')

@section('content_header')
    <h1 class="font-weight-bold">Detail Pembayaran Penjualan</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="160">No. Invoice</th>
                            <td>: {{ $pembayaran->invoice->nomor_invoice }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar</th>
                            <td>: {{ tanggal_indonesia($pembayaran->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th>Metode Bayar</th>
                            <td>: {{ $pembayaran->metodePembayaran->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Dibayar</th>
                            <td>: Rp {{ number_format($pembayaran->jumlah, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="160">Pelanggan</th>
                            <td>: {{ $pembayaran->invoice->salesOrder->pelanggan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. SO</th>
                            <td>: {{ $pembayaran->invoice->salesOrder->nomor_so ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal SO</th>
                            <td>: {{ tanggal_indonesia($pembayaran->invoice->salesOrder->tanggal ?? '') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Diskon</th>
                            <th>Setelah Diskon</th>
                            <th>PPN (11%)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = $totalDiskon = $totalPPN = $grandTotal = 0;
                        @endphp
                        @foreach ($pembayaran->invoice->salesOrder->details as $detail)
                            @php
                                $totalHarga = $detail->qty * $detail->harga;
                                $hargaSetelahDiskon = $totalHarga - $detail->diskon;
                                $ppn = $hargaSetelahDiskon * 0.11;
                                $hargaNett = $hargaSetelahDiskon + $ppn;

                                $subtotal += $totalHarga;
                                $totalDiskon += $detail->diskon;
                                $totalPPN += $ppn;
                                $grandTotal += $hargaNett;
                            @endphp
                            <tr>
                                <td>{{ $detail->produk->nama ?? '-' }}</td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end">Rp {{ number_format($detail->harga, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($totalHarga, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->diskon, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($hargaSetelahDiskon, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($ppn, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($hargaNett, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light text-end">
                        <tr>
                            <th colspan="3" class="text-center">TOTAL</th>
                            <th>Rp {{ number_format($subtotal, 2, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalDiskon, 2, ',', '.') }}</th>
                            <th>Rp {{ number_format($subtotal - $totalDiskon, 2, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalPPN, 2, ',', '.') }}</th>
                            <th>Rp {{ number_format($grandTotal, 2, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('pembayaran-penjualan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('pembayaran-penjualan.cetakPdf', $pembayaran->id) }}" target="_blank"
                    class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        @stop
