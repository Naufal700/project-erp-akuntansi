@extends('adminlte::page')

@section('title', 'Detail Purchase Order')

@section('content_header')
    <h1 class="font-weight-bold">Detail Purchase Order</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nomor PO:</strong> {{ $po->nomor_po }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}</p>
                    <p><strong>Supplier:</strong> {{ $po->supplier->nama }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <span
                        class="badge 
                    @if ($po->status == 'pending') bg-warning 
                    @elseif($po->status == 'approved') bg-success 
                    @elseif($po->status == 'rejected') bg-danger 
                    @else bg-secondary @endif
                    p-2">
                        Status: {{ ucfirst($po->status) }}
                    </span>
                </div>
            </div>

            <hr>

            <h5 class="font-weight-bold">Detail Produk</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po->details as $item)
                            <tr>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-end">Rp{{ number_format($item->harga, 2, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end">Rp{{ number_format($po->total, 2, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary">Kembali</a>
                @if ($po->status == 'pending')
                    <a href="{{ route('purchase-order.edit', $po->id) }}" class="btn btn-warning">Edit</a>
                @endif
            </div>
        </div>
    </div>
@endsection
