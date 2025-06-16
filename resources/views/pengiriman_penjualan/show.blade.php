@extends('adminlte::page')

@section('title', 'Surat Jalan #' . $pengiriman->nomor_surat_jalan)

@section('content_header')
    <h1 class="mb-3">Detail Surat Jalan</h1>
@stop

@section('content')
    <div class="row">
        <!-- Informasi Surat Jalan -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-truck"></i> Informasi Surat Jalan
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th style="width: 170px;">Nomor Surat Jalan</th>
                            <td>: <strong>{{ $pengiriman->nomor_surat_jalan }}</strong></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>: {{ \Carbon\Carbon::parse($pengiriman->tanggal)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status Pengiriman</th>
                            <td>
                                @php
                                    $status = $pengiriman->status_pengiriman;
                                    $badgeClass = 'secondary';
                                    $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                    if ($status == 'selesai') {
                                        $badgeClass = 'success';
                                    } elseif ($status == 'dalam_proses') {
                                        $badgeClass = 'warning text-dark';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informasi Pelanggan -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-user"></i> Informasi Pelanggan
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th style="width: 170px;">Nama Pelanggan</th>
                            <td>: {{ $pengiriman->salesOrder->pelanggan->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: {{ $pengiriman->salesOrder->pelanggan->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>: {{ $pengiriman->salesOrder->pelanggan->telepon ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Produk -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-boxes"></i> Detail Produk dalam Surat Jalan
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover m-0" style="font-size: 0.9rem;">
                <thead class="table-light text-center align-middle">
                    <tr>
                        <th style="width:5%;">No</th>
                        <th>Nama Produk</th>
                        <th style="width:10%;">Qty</th>
                        <th style="width:10%;">Satuan</th>
                        <th style="width:15%;" class="text-end">Harga (Rp)</th>
                        <th style="width:20%;" class="text-end">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($pengiriman->salesOrder->salesOrderDetail ?? [] as $index => $detail)
                        @php
                            $qty = $detail->qty ?? 0;
                            $harga = $detail->harga ?? 0;
                            $subtotal = $qty * $harga;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $detail->produk->nama ?? '-' }}</td>
                            <td class="text-center align-middle">{{ $qty }}</td>
                            <td class="text-center align-middle">{{ $detail->produk->satuan ?? '-' }}</td>
                            <td class="text-end align-middle">{{ number_format($harga, 0, ',', '.') }}</td>
                            <td class="text-end align-middle">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="fw-bold bg-light">
                    <tr class="text-end">
                        <td colspan="5">Total</td>
                        <td>{{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('pengiriman-penjualan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('pengiriman-penjualan.cetak-pdf', $pengiriman->id) }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>
@stop
