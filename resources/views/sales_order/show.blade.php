@extends('adminlte::page')

@section('title', 'Detail Sales Order')

@section('content_header')
    <h1 class="mb-4">Detail Pesanan Penjualan #{{ $order->nomor_so }}</h1>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row text-sm">
                <div class="col-md-3 mb-2">
                    <strong>Tanggal:</strong> <br>
                    {{ \Carbon\Carbon::parse($order->tanggal)->format('d-m-Y') }}
                </div>
                <div class="col-md-4 mb-2">
                    <strong>Customer:</strong> <br>
                    {{ $order->customer->nama }}
                </div>
                <div class="col-md-3 mb-2">
                    <strong>Status:</strong> <br>
                    @php
                        $statusClass = match (strtolower($order->status)) {
                            'pending' => 'badge-warning',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                            default => 'badge-secondary',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="col-md-2 mb-2">
                    <strong>Total:</strong> <br>
                    <span class="text-primary font-weight-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Detail Produk</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark text-center">
                <tr>
                    <th>Produk</th>
                    <th style="width:80px;">Qty</th>
                    <th style="width:120px;">Harga</th>
                    <th style="width:120px;">Diskon (Rp)</th>
                    {{-- <th>Diskon (%)</th> --}}
                    <th style="width:130px;">Subtotal</th>
                    <th style="width:130px;">Total Item (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->details as $detail)
                    @php
                        $hargaTotal = $detail->harga * $detail->qty;
                        $diskonNominal = $detail->diskon;
                        $diskonPersen = $hargaTotal > 0 ? ($diskonNominal / $hargaTotal) * 100 : 0;
                        // Hilangkan perhitungan PPN
                        $totalItem = $detail->subtotal; // tanpa PPN
                    @endphp
                    <tr>
                        <td>{{ $detail->produk->nama }}</td>
                        <td class="text-center">{{ $detail->qty }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-right" title="{{ number_format($diskonPersen, 2) }}%">Rp
                            {{ number_format($diskonNominal, 0, ',', '.') }}</td>
                        {{-- <td>{{ number_format($diskonPersen) }}%</td> --}}
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('sales_order.index') }}" class="btn btn-secondary mt-4">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
    <form action="{{ route('sales_order.reject', $order->id) }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-danger mt-4" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
            <i class="fas fa-times mr-1"></i> Batal
        </button>
    </form>

    <style>
        /* Hover effect on rows */
        tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Make the card header font a bit bigger and bolder */
        .card-body strong {
            font-size: 1rem;
        }
    </style>
@endsection
