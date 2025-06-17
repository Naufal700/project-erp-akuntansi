@extends('adminlte::page')

@section('title', 'Kartu Stok')

@section('content_header')
    <h1 class="text-bold">Kartu Stok</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('kartu-stok.index') }}" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" class="form-control"
                        value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-2">
                    <label>Jenis</label>
                    <select name="jenis" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Customer/Supplier</label>
                    <input type="text" name="sumber_tujuan" class="form-control"
                        placeholder="Nama customer atau supplier" value="{{ request('sumber_tujuan') }}">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </form>
            <a href="{{ route('kartu-stok.export.excel', request()->query()) }}" class="btn btn-success">
                Export Excel
            </a>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Transaksi</th>
                                <th>No Transaksi</th>
                                <th>Nama Produk</th>
                                <th>Customer / Supplier</th>
                                <th>Saldo Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kartuStok as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $item->no_transaksi }}</td>
                                    <td>{{ $item->produk->nama ?? '-' }}</td>
                                    <td>{{ $item->sumber_tujuan ?? '-' }}</td>
                                    <td>{{ $item->saldo_awal }}</td>
                                    <td>{{ $item->masuk }}</td>
                                    <td>{{ $item->keluar }}</td>
                                    <td>{{ $item->saldo_akhir }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data kartu stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="card-footer clearfix d-flex justify-content-end">
                        {{ $kartuStok->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    @stop
