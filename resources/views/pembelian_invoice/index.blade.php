@extends('adminlte::page')
@section('title', 'Faktur Pembelian')
@section('content_header')
    <h1 class="font-weight-bold">Faktur Pembelian</h1>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari Faktur..."
                        value="{{ request('search') }}" aria-label="Cari Faktur">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-search">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('pembelian-invoice.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('pembelian-invoice.create') }}" class="btn btn-success mr-2"
                    title="Tambah Faktur Pembelian">
                    <i class="fas fa-plus-circle"></i> Faktur Pembelian
                </a>
                <form method="GET" action="{{ route('pembelian-invoice.index') }}" class="form-inline">
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="form-control mr-2" placeholder="Dari Tanggal">
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="form-control mr-2" placeholder="Sampai Tanggal">
                    <select name="status" class="form-control mr-2">
                        <option value="">-- Semua Status --</option>
                        <option value="belum_dikontrabon" {{ request('status') == 'belum_dikontrabon' ? 'selected' : '' }}>
                            Belum dikontrabon
                        </option>
                        <option value="dikontrabon" {{ request('status') == 'dikontrabon' ? 'selected' : '' }}>
                            Dikontrabon
                        </option>
                        <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>
                            Dibayar
                        </option>
                    </select>
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Nomor Faktur</th>
                        <th>Tanggal Faktur Pembelian</th>
                        <th>Nomor Faktur Pajak</th>
                        <th>Tanggal Faktur Pajak</th>
                        <th>Supplier</th>
                        <th>Total Faktur</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nomor_invoice }}</td>
                            <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                            <td>{{ $item->nomor_faktur_pajak ?? '-' }}</td>
                            <td>{{ $item->tanggal_faktur_pajak ? tanggal_indonesia($item->tanggal_faktur_pajak) : '-' }}
                            </td>
                            <td>{{ $item->penerimaan?->purchaseOrder?->supplier?->nama ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item->total, 2, ',', '.') }}</td>
                            <td><span
                                    class="badge {{ $item->status == 'dibayar' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                            </td>
                            <td>{{ tanggal_indonesia($item->jatuh_tempo) }}</td>
                            <td>
                                <a href="{{ route('pembelian-invoice.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if ($item->status === 'belum_dikontrabon')
                                    <form action="{{ route('pembelian-invoice.batal', $item->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan faktur ini?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Batalkan Faktur">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $invoices->links() }}
        @stop
