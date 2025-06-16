@extends('adminlte::page')

@section('title', 'Laporan Persediaan')

@section('content_header')
    <h1>Laporan Persediaan (FIFO)</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('laporan-persediaan.index') }}" method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="start" class="mr-1">Dari</label>
                    <input type="date" name="start_date" id="start" class="form-control"
                        value="{{ request('start_date') ?? now()->startOfMonth()->toDateString() }}">
                </div>
                <div class="form-group mr-2">
                    <label for="end" class="mr-1">Sampai</label>
                    <input type="date" name="end_date" id="end" class="form-control"
                        value="{{ request('end_date') ?? now()->endOfMonth()->toDateString() }}">
                </div>
                <button type="submit" class="btn btn-primary mr-2">Filter</button>

                @if ($closingExists)
                    <a href="{{ route('laporan-persediaan.export', request()->all()) }}" class="btn btn-success mr-2">
                        Export Excel
                    </a>
                @else
                    <button class="btn btn-secondary mr-2" disabled>Export Excel</button>
                @endif
            </form>

            @if ($closingExists)
                <form action="{{ route('laporan-persediaan.closing.manual') }}" method="POST" class="form-inline"
                    onsubmit="return confirm('Yakin ingin melakukan closing manual?')">
                    @csrf
                    <div class="form-group mr-2">
                        <label for="closing_date" class="mr-1">Tanggal Closing</label>
                        <input type="date" name="tanggal" id="closing_date" class="form-control"
                            value="{{ old('tanggal') ?? $prevMonthEnd }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Closing Persediaan</button>
                </form>
            @else
                <div class="alert alert-info mt-3">
                    Belum ada data <strong>saldo_awal</strong> di tanggal <strong>{{ $prevMonthEnd }}</strong>.
                    Silakan lakukan closing persediaan terlebih dahulu.
                </div>
            @endif
        </div>

        @if ($closingExists)
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th rowspan="2">Kode Produk</th>
                                <th rowspan="2">Nama</th>
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Kategori</th>
                                <th colspan="3">Saldo Awal</th>
                                <th colspan="3">Penerimaan</th>
                                <th colspan="3">Pengeluaran</th>
                                <th colspan="3">Saldo Akhir</th>
                            </tr>
                            <tr>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkList as $p)
                                <tr>
                                    <td>{{ $p->kode_produk }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->satuan }}</td>
                                    <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>

                                    {{-- Saldo Awal --}}
                                    <td class="text-right">{{ $p->saldo_awal_qty }}</td>
                                    <td class="text-right">{{ number_format($p->saldo_awal_harga, 0) }}</td>
                                    <td class="text-right">
                                        {{ number_format($p->saldo_awal_qty * $p->saldo_awal_harga, 0) }}
                                    </td>

                                    {{-- Penerimaan --}}
                                    <td class="text-right">{{ $p->penerimaan_qty }}</td>
                                    <td class="text-right">{{ number_format($p->penerimaan_harga, 0) }}</td>
                                    <td class="text-right">
                                        {{ number_format($p->penerimaan_qty * $p->penerimaan_harga, 0) }}
                                    </td>

                                    {{-- Pengeluaran --}}
                                    <td class="text-right">{{ $p->pengeluaran_qty }}</td>
                                    <td class="text-right">{{ number_format($p->pengeluaran_harga, 0) }}</td>
                                    <td class="text-right">
                                        {{ number_format($p->pengeluaran_qty * $p->pengeluaran_harga, 0) }}
                                    </td>

                                    {{-- Saldo Akhir --}}
                                    <td class="text-right">{{ $p->saldo_akhir_qty }}</td>
                                    <td class="text-right">{{ number_format($p->saldo_akhir_harga, 0) }}</td>
                                    <td class="text-right">
                                        {{ number_format($p->saldo_akhir_qty * $p->saldo_akhir_harga, 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="17" class="text-center text-muted">Data tidak tersedia.</td>
                                </tr>
                            @endforelse

                            {{-- Total row --}}
                            <tr class="table-primary font-weight-bold text-right">
                                <td colspan="4" class="text-center">Total</td>

                                <td>{{ number_format($total['saldo_awal_qty'], 0) }}</td>
                                <td>-</td>
                                <td>{{ number_format($total['saldo_awal_total'], 0) }}</td>

                                <td>{{ number_format($total['penerimaan_qty'], 0) }}</td>
                                <td>-</td>
                                <td>{{ number_format($total['penerimaan_total'], 0) }}</td>

                                <td>{{ number_format($total['pengeluaran_qty'], 0) }}</td>
                                <td>-</td>
                                <td>{{ number_format($total['pengeluaran_total'], 0) }}</td>

                                <td>{{ number_format($total['saldo_akhir_qty'], 0) }}</td>
                                <td>-</td>
                                <td>{{ number_format($total['saldo_akhir_total'], 0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="card-footer clearfix d-flex justify-content-end">
                        {{ $produkList->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-muted my-5">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <p class="lead">Laporan belum tersedia karena belum dilakukan closing persediaan bulan sebelumnya.</p>
            </div>
        @endif
    </div>
@stop
