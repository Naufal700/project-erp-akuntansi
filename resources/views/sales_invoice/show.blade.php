@extends('adminlte::page')

@section('title', 'Detail Faktur Penjualan')

@section('content_header')
    <h1 class="mb-3">Detail Faktur Penjualan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Informasi Header: Perusahaan & Invoice -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><strong>PT. Contoh Perusahaan</strong></h5>
                    <address>
                        Jl. Contoh Alamat No.123<br>
                        Jakarta, Indonesia<br>
                        Telp: (021) 12345678<br>
                        Email: info@contohperusahaan.co.id
                    </address>
                </div>
                <div class="col-md-6 text-md-end">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th width="140">Nomor Invoice</th>
                            <td>: <strong>{{ $invoice->nomor_invoice }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal Invoice</th>
                            <td>: {{ tanggal_indonesia($invoice->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>:
                                <span class="badge bg-{{ $invoice->status == 'lunas' ? 'success' : 'warning' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Info Pelanggan & Sales Order -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Data Pelanggan</strong></h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="140">Nama</th>
                            <td>: {{ $invoice->salesOrder->pelanggan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $invoice->salesOrder->pelanggan->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $invoice->salesOrder->pelanggan->telepon ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><strong>Info Sales Order</strong></h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="140">Nomor SO</th>
                            <td>: {{ $invoice->salesOrder->nomor_so ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal SO</th>
                            <td>: {{ tanggal_indonesia($invoice->salesOrder->tanggal ?? '') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Tabel Produk -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
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
                        @foreach ($invoice->salesOrder->details as $detail)
                            @php
                                $totalHarga = $detail->qty * $detail->harga;
                                $setelahDiskon = $totalHarga - $detail->diskon;
                                $ppn = $setelahDiskon * 0.11;
                                $total = $setelahDiskon + $ppn;

                                $subtotal += $totalHarga;
                                $totalDiskon += $detail->diskon;
                                $totalPPN += $ppn;
                                $grandTotal += $total;
                            @endphp
                            <tr>
                                <td>{{ $detail->produk->nama ?? '-' }}</td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end">Rp {{ number_format($detail->harga, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($totalHarga, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->diskon, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($setelahDiskon, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($ppn, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="text-end bg-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-center">TOTAL</td>
                            <td>Rp {{ number_format($subtotal, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalDiskon, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($subtotal - $totalDiskon, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalPPN, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($grandTotal, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('sales-invoice.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('sales-invoice.printPdf', $invoice->id) }}" target="_blank"
                    class="btn btn-outline-danger">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
    </div>
@stop
