@extends('adminlte::page')

@section('title', 'Data Produk')

@section('content_header')
    <h3>Master Produk</h3>
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
            {{-- Form Filter & Aksi --}}
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama produk...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>

                <div class="input-group mr-2">
                    <input type="month" name="filter_bulan" value="{{ request('filter_bulan') }}" class="form-control"
                        placeholder="Filter Bulan">
                </div>

                <a href="{{ route('produk.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('produk.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus-circle"></i> Tambah Produk
                </a>
                <a href="{{ route('produk.downloadTemplate') }}" class="btn btn-info mr-2">
                    <i class="fas fa-download"></i> Download Template
                </a>
                <a href="{{ route('produk.exportPdf') }}" class="btn btn-danger mr-2">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </form>

            {{-- Form Import --}}
            <form action="{{ route('produk.import') }}" method="POST" enctype="multipart/form-data"
                class="form-inline mb-3">
                @csrf
                <div class="form-group mr-2">
                    <input type="file" name="file" accept=".xls,.xlsx" required class="form-control">
                </div>
                <div class="form-group mr-2">
                    <input type="month" name="bulan_saldo_awal" required class="form-control">
                </div>
                <button class="btn btn-warning">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
            </form>

            {{-- Tabel Produk --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Saldo Awal Qty</th>
                            <th>Saldo Awal Harga</th>
                            <th>Stok</th>
                            <th>Perubahan Stok Bulan Ini</th>
                            <th>Minimal</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th>Stok Tipe</th>
                            <th>Rak</th>
                            <th>Dibuat</th>
                            <th>Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $row)
                            <tr>
                                <td>{{ $row->kode_produk }}</td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->satuan }}</td>
                                <td>{{ number_format($row->harga_beli, 2) }}</td>
                                <td>{{ number_format($row->harga_jual, 2) }}</td>
                                <td>{{ number_format($row->saldo_awal_qty, 0) }}</td>
                                <td>{{ number_format($row->saldo_awal_harga, 2) }}</td>
                                <td>{{ $row->stok }}</td>
                                <td>
                                    @php
                                        $bulan = request('filter_bulan') ?? now()->format('Y-m');
                                        $start = \Carbon\Carbon::parse($bulan . '-01')->startOfMonth();
                                        $end = \Carbon\Carbon::parse($bulan . '-01')->endOfMonth();

                                        $perubahanStok = \App\Models\TransaksiPersediaan::where(
                                            'kode_produk',
                                            $row->kode_produk,
                                        )
                                            ->whereBetween('tanggal', [$start, $end])
                                            ->sum('qty');
                                    @endphp
                                    {{ $perubahanStok }}
                                </td>
                                <td>{{ $row->stok_minimal }}</td>
                                <td>{{ optional($row->kategori)->nama_kategori }}</td>
                                <td>{{ optional($row->supplier)->nama }}</td>
                                <td>{{ strtoupper($row->tipe_stok) }}</td>
                                <td>{{ $row->lokasi_rak }}</td>
                                <td>{{ $row->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if ($row->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('produk.edit', $row->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i></a>
                                    <form action="{{ route('produk.destroy', $row->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin Mau Hapus Produk Ini?')"
                                            class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $produk->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
