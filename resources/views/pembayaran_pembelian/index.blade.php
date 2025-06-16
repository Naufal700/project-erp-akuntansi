@extends('adminlte::page')

@section('title', 'Pembayaran Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Daftar Pembayaran Supplier</h1>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari Pembayaran..."
                        value="{{ request('search') }}" aria-label="Cari Pembayaran">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-search">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('pembayaran-pembelian.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('pembayaran-pembelian.create') }}" class="btn btn-success mr-2"
                    title="Tambah Pembayaran Pembelian">
                    <i class="fas fa-plus-circle"></i> Pembayaran
                </a>
                <form method="GET" action="{{ route('pembelian-invoice.index') }}" class="form-inline">
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="form-control mr-2" placeholder="Dari Tanggal">
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="form-control mr-2" placeholder="Sampai Tanggal">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Nomor Kontrabon</th>
                        <th>Metode</th>
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $row)
                        @php
                            $kontrabon = $row->kontrabon;
                            $totalTagihan = $kontrabon->total ?? 0;
                            $totalBayar = $kontrabon->pembayaran->sum('jumlah') ?? 0;
                            $sisaTagihan = $totalTagihan - $totalBayar;
                            $supplierNama = $kontrabon->supplier->nama ?? '-';
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ tanggal_indonesia($row->tanggal) }}</td>
                            <td>{{ $supplierNama }}</td>
                            <td>{{ $kontrabon->nomor_kontrabon ?? '-' }}</td>
                            <td>{{ $row->metode ?? '-' }}</td>
                            <td class="text-end">{{ number_format($totalTagihan, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($totalBayar, 2, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('pembayaran-pembelian.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('Batalkan pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Batalkan Faktur">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    @stop
