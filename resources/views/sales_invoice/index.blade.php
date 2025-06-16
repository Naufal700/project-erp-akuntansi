@extends('adminlte::page')

@section('title', 'Faktur Penjualan')

@section('content_header')
    <h1>Daftar Faktur Penjualan</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari faktur penjualan...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('sales-invoice.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('sales-invoice.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus-circle"></i> Faktur Penjualan
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor Invoice</th>
                            <th>Sales Order</th>
                            <th>Nama Pelanggan</th>
                            <th>Harga Setelah Diskon</th>
                            <th>PPN (11%)</th>
                            <th>Total Nett</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td>{{ tanggal_indonesia($invoice->tanggal) }}</td>
                                <td>{{ $invoice->nomor_invoice }}</td>
                                <td>{{ $invoice->salesOrder->nomor_so ?? '-' }}</td>
                                <td>{{ $invoice->salesOrder->customer->nama ?? '-' }}</td>

                                {{-- Harga Setelah Diskon (ambil dari salesOrder total) --}}
                                <td>{{ number_format($invoice->salesOrder->total ?? 0, 2) }}</td>

                                {{-- PPN --}}
                                <td>{{ number_format($invoice->ppn ?? 0, 2) }}</td>

                                {{-- Total Nett (Harga Setelah Diskon + PPN) --}}
                                <td>{{ number_format(($invoice->salesOrder->total ?? 0) + ($invoice->ppn ?? 0), 2) }}</td>

                                <td>{{ $invoice->jatuh_tempo ? $invoice->jatuh_tempo->format('d-m-Y') : '-' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</td>
                                <td>
                                    <a href="{{ route('sales-invoice.show', $invoice->id) }}" class="btn btn-sm btn-info"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($invoice->status !== 'batal')
                                        <form action="{{ route('sales-invoice.cancel', $invoice->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" title="Batalkan Faktur"
                                                onclick="return confirm('Batalkan faktur ini?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('sales-invoice.destroy', $invoice->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Faktur"
                                                onclick="return confirm('Hapus faktur ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Data faktur tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@stop
