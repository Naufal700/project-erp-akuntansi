@extends('adminlte::page')

@section('title', 'Detail Penerimaan Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Detail Penerimaan Pembelian</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <p><strong>No. Penerimaan:</strong> {{ $penerimaan->nomor_penerimaan }}</p>
                    <p><strong>Nomor PO:</strong> {{ $penerimaan->purchaseOrder->nomor_po ?? '-' }}</p>
                    <p><strong>Supplier:</strong> {{ $penerimaan->purchaseOrder->supplier->nama ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal:</strong> {{ tanggal_indonesia($penerimaan->tanggal) }}</p>
                    <p><strong>Status:</strong>
                        @php
                            $statusColors = [
                                'belum_faktur' => 'warning',
                                'diterima' => 'success',
                                'dibatalkan' => 'danger',
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$penerimaan->status] ?? 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $penerimaan->status)) }}
                        </span>
                    </p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3 font-weight-bold">Detail Barang Diterima</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>Nama Produk</th>
                            <th>Qty Dipesan</th>
                            <th>Qty Diterima</th>
                            <th>Harga PO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penerimaan->detail as $item)
                            <tr>
                                <td>{{ $item->produk->nama ?? '-' }}</td>
                                <td class="text-center">
                                    {{ optional($item->purchaseOrderDetail)->qty ?? '-' }}
                                </td>
                                <td class="text-center">{{ $item->qty_diterima }}</td>
                                <td class="text-end">{{ formatCurrency($item->purchaseOrderDetail->harga ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
@stop
