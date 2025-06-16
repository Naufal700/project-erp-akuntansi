@extends('adminlte::page')

@section('title', 'Pembayaran Penjualan')

@section('content_header')
    <h1>Pembayaran Penjualan</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari supplier..."
                        value="{{ request('search') }}" aria-label="Cari pembayaran">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-search">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('pembayaran-penjualan.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('pembayaran-penjualan.create') }}" class="btn btn-success mr-2" title="Tambah SO">
                    <i class="fas fa-plus-circle"></i> Pembayaran
                </a>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Invoice</th>
                            <th>Nama Pelanggan</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                                <td>{{ $item->invoice->nomor_invoice }}</td>
                                <td>{{ $item->invoice->salesOrder->customer->nama ?? '-' }}</td>
                                <td>{{ $item->metodePembayaran->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($item->jumlah, 2, ',', '.') }}</td>
                                <td class="d-flex">
                                    <a href="{{ route('pembayaran-penjualan.show', $item->id) }}"
                                        class="btn btn-sm btn-info mr-1">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <form action="{{ route('pembayaran-penjualan.batal', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Batalkan pembayaran ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-warning mr-1">Batal</button>
                                    </form>
                                    <form action="{{ route('pembayaran-penjualan.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @stop
