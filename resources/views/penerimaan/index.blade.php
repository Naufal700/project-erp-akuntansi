@extends('adminlte::page')

@section('title', 'Penerimaan Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Penerimaan Pembelian</h1>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari Penerimaan..."
                        value="{{ request('search') }}" aria-label="Cari nomor po">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-search">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('penerimaan.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('penerimaan.create') }}" class="btn btn-success mr-2" title="Tambah PO">
                    <i class="fas fa-plus-circle"></i> Tambah Penerimaan
                </a>
                <form method="GET" action="{{ route('penerimaan.index') }}" class="form-inline">
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
                    <tr class="text-center">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nomor PO</th>
                        <th>Nomor Penerimaan</th>
                        <th>Supplier</th>
                        <th>Total Barang</th>
                        <th>Status</th>
                        <th style="width: 140px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($penerimaan as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                            <td>{{ $item->purchaseOrder->nomor_po ?? '-' }}</td>
                            <td>{{ $item->nomor_penerimaan }}</td>
                            <td>{{ $item->purchaseOrder->supplier->nama ?? '-' }}</td>
                            <td class="text-center">{{ $item->detail->sum('qty_diterima') }}</td>
                            <td class="text-center">
                                @php
                                    $statusColors = [
                                        'belum_faktur' => 'warning',
                                        'diterima' => 'success',
                                        'dibatalkan' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('penerimaan.show', $item->id) }}" class="btn btn-sm btn-info"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('penerimaan.destroy', $item->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data penerimaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @stop
