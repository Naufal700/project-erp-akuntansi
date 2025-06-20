@extends('adminlte::page')

@section('title', 'Neraca Lajur')

@section('content_header')
    <h1 class="mb-1 fw-bold">Neraca Lajur</h1>
    <p class="text-muted">Periode: {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}</p>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-3">
                    <label for="periode" class="form-label fw-semibold">Pilih Periode</label>
                    <input type="month" id="periode" name="periode" class="form-control" value="{{ $periode }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Tampilkan
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('neraca-lajur.export', ['periode' => $periode]) }}" class="btn btn-success w-100">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </form>

            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover text-nowrap align-middle">
                    <thead class="thead-dark sticky-top text-center">
                        <tr>
                            <th rowspan="2" class="text-nowrap">Kode Akun</th>
                            <th rowspan="2" class="text-nowrap">Nama Akun</th>
                            <th colspan="2">Saldo Awal</th>
                            <th colspan="2">Mutasi</th>
                            <th colspan="2">Neraca Saldo</th>
                            <th colspan="2">Penyesuaian</th>
                            <th colspan="2">Neraca Disesuaikan</th>
                            <th colspan="2">Laba Rugi</th>
                            <th colspan="2">Neraca</th>
                        </tr>
                        <tr>
                            @for ($i = 0; $i < 7; $i++)
                                <th class="text-nowrap">Debet</th>
                                <th class="text-nowrap">Kredit</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = array_fill_keys(
                                [
                                    'saldo_awal_debit',
                                    'saldo_awal_kredit',
                                    'mutasi_debit',
                                    'mutasi_kredit',
                                    'neraca_saldo_debit',
                                    'neraca_saldo_kredit',
                                    'penyesuaian_debit',
                                    'penyesuaian_kredit',
                                    'neraca_sesudah_debit',
                                    'neraca_sesudah_kredit',
                                    'laba_rugi_debit',
                                    'laba_rugi_kredit',
                                    'neraca_debit',
                                    'neraca_kredit',
                                ],
                                0,
                            );

                            $filteredData = $data->filter(function ($akun) {
                                return collect($akun)
                                    ->except(['kode_akun', 'nama_akun', 'level'])
                                    ->map(fn($val) => (float) str_replace(['.', ','], '', $val))
                                    ->sum() > 0;
                            });

                            $currentPage = request()->get('page', 1);
                            $perPage = 20;
                            $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
                                $filteredData->forPage($currentPage, $perPage),
                                $filteredData->count(),
                                $perPage,
                                $currentPage,
                                ['path' => request()->url(), 'query' => request()->query()],
                            );
                        @endphp

                        @forelse ($pagedData as $akun)
                            <tr>
                                <td class="text-nowrap">{{ $akun['kode_akun'] }}</td>
                                <td class="text-nowrap" style="padding-left: {{ $akun['level'] * 15 }}px">
                                    {{ $akun['nama_akun'] }}
                                </td>
                                @foreach (['saldo_awal_debit', 'saldo_awal_kredit', 'mutasi_debit', 'mutasi_kredit', 'neraca_saldo_debit', 'neraca_saldo_kredit', 'penyesuaian_debit', 'penyesuaian_kredit', 'neraca_sesudah_debit', 'neraca_sesudah_kredit', 'laba_rugi_debit', 'laba_rugi_kredit', 'neraca_debit', 'neraca_kredit'] as $kolom)
                                    @php
                                        $nilai = (float) $akun[$kolom];
                                        $total[$kolom] += $nilai;
                                        $tampilkan = in_array($kolom, [
                                            'saldo_awal_kredit',
                                            'mutasi_kredit',
                                            'neraca_saldo_kredit',
                                            'penyesuaian_kredit',
                                            'neraca_sesudah_kredit',
                                            'laba_rugi_kredit',
                                            'neraca_kredit',
                                        ])
                                            ? abs($nilai)
                                            : $nilai;
                                    @endphp
                                    <td class="text-end text-dark text-nowrap">
                                        {{ number_format($tampilkan, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center text-muted">Tidak ada data untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-secondary fw-bold text-end">
                        <tr>
                            <td colspan="2" class="text-center text-nowrap">TOTAL</td>
                            @foreach ($total as $kolom => $jumlah)
                                @php
                                    $tampilkan = in_array($kolom, [
                                        'saldo_awal_kredit',
                                        'mutasi_kredit',
                                        'neraca_saldo_kredit',
                                        'penyesuaian_kredit',
                                        'neraca_sesudah_kredit',
                                        'laba_rugi_kredit',
                                        'neraca_kredit',
                                    ])
                                        ? abs($jumlah)
                                        : $jumlah;
                                @endphp
                                <td class="text-nowrap">
                                    {{ number_format($tampilkan, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $pagedData->links() }}
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $('#tabelNeraca').DataTable({
                paging: false,
                scrollX: true,
                searching: true,
                ordering: false,
                info: false,
                language: {
                    search: "Cari Akun:",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Tidak ada data ditampilkan"
                }
            });
        });
    </script>
@stop
