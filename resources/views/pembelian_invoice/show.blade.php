@extends('adminlte::page')

@section('title', 'Detail Faktur Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Detail Faktur Pembelian</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Header Informasi Faktur --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%">Nomor Faktur</th>
                            <td>: {{ $invoice->nomor_invoice }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Faktur</th>
                            <td>: {{ tanggal_indonesia($invoice->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nomor Faktur Pajak</th>
                            <td>: {{ $invoice->nomor_faktur_pajak ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Faktur Pajak</th>
                            <td>:
                                {{ $invoice->tanggal_faktur_pajak ? tanggal_indonesia($invoice->tanggal_faktur_pajak) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>: <span class="badge {{ $invoice->status == 'dibayar' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Jatuh Tempo</th>
                            <td>: {{ $invoice->jatuh_tempo ? tanggal_indonesia($invoice->jatuh_tempo) : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Pembayaran</th>
                            <td>: {{ $invoice->tanggal_pembayaran ? tanggal_indonesia($invoice->tanggal_pembayaran) : '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%">Nomor PO</th>
                            <td>: {{ $invoice->penerimaan->purchaseOrder->nomor_po ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Supplier</th>
                            <td>: {{ $invoice->penerimaan->purchaseOrder->supplier->nama ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            {{-- Rincian Pembayaran --}}
            <h5 class="mb-3">Rincian Pembayaran</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Bruto</th>
                            <th>Diskon</th>
                            <th>Harga Setelah Diskon</th>
                            <th>PPN (11%)</th>
                            <th>Harga Netto</th>
                        </tr>
                    </thead>
                    <tbody class="text-end">
                        @php
                            // Hitung total harga setelah diskon semua item untuk alokasi PPN proporsional
                            $totalSetelahDiskon = $invoice->details->sum(function ($d) {
                                return $d->harga * $d->qty - $d->diskon;
                            });
                        @endphp

                        @foreach ($invoice->details as $detail)
                            @php
                                $satuan = $detail->produk->satuan;
                                $hargaSatuan = $detail->harga;
                                $qty = $detail->qty;
                                $bruto = $hargaSatuan * $qty;
                                $diskon = $detail->diskon;
                                $hargaSetelahDiskon = $bruto - $diskon;

                                // Alokasi PPN secara proporsional berdasarkan harga setelah diskon
                                $ppn =
                                    $totalSetelahDiskon > 0
                                        ? ($hargaSetelahDiskon / $totalSetelahDiskon) * $invoice->ppn
                                        : 0;

                                $hargaNetto = $hargaSetelahDiskon + $ppn;
                            @endphp
                            <tr>
                                <td class="text-start">{{ $detail->produk->nama }}</td>
                                <td>{{ $qty }}</td>
                                <td>{{ $satuan }}</td>
                                <td>Rp {{ number_format($hargaSatuan, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($bruto, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($diskon, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($hargaSetelahDiskon, 2, ',', '.') }}</td>
                                <td>Rp {{ number_format($ppn, 2, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($hargaNetto, 2, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('pembelian-invoice.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </div>
    </div>
@stop
