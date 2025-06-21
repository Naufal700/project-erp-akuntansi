@extends('adminlte::page')

@section('title', 'Neraca Saldo')

@section('content_header')
    <h1 class="mb-3 font-weight-bold text-primary"><i class="fas fa-balance-scale"></i> Neraca Saldo</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <form method="GET" action="{{ url()->current() }}" class="mb-0" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="filter_type">Filter:</label>
                        <select name="filter_type" class="form-control" id="filter_type">
                            <option value="bulan" {{ request('filter_type', 'bulan') == 'bulan' ? 'selected' : '' }}>Bulan
                            </option>
                            <option value="periode" {{ request('filter_type') == 'periode' ? 'selected' : '' }}>Periode
                                (Tanggal)</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="bulanFilter">
                        <label for="bulan">Pilih Bulan:</label>
                        <input type="month" name="bulan" id="bulan" class="form-control"
                            value="{{ request('bulan') }}">
                    </div>
                    <div class="col-md-6 d-none" id="periodeFilter">
                        <label>Periode Tanggal:</label>
                        <div class="d-flex gap-2">
                            <input type="date" name="tanggal_awal" class="form-control" placeholder="Dari"
                                value="{{ request('tanggal_awal') }}">
                            <input type="date" name="tanggal_akhir" class="form-control" placeholder="Sampai"
                                value="{{ request('tanggal_akhir') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-1"></i> Tampilkan
                    </button>
                    <a href="{{ route('neraca.exportExcel', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-hover text-nowrap align-middle mb-0">
                <thead class="thead-dark text-center sticky-top" style="top: 0; z-index: 1020;">
                    <tr>
                        <th rowspan="2">Kode Akun</th>
                        <th rowspan="2">Nama Akun</th>
                        <th colspan="2">Saldo Awal</th>
                        <th colspan="2">Mutasi</th>
                        <th colspan="2">Saldo Akhir</th>
                    </tr>
                    <tr>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_saldo_awal_debit = 0;
                        $total_saldo_awal_kredit = 0;
                        $total_mutasi_debit = 0;
                        $total_mutasi_kredit = 0;
                        $total_saldo_akhir_debit = 0;
                        $total_saldo_akhir_kredit = 0;
                    @endphp
                    @php $data_tersedia = false; @endphp

                    @foreach ($data_neraca as $akun)
                        @php
                            $saldo_awal = ($akun->saldo_awal_debit ?? 0) - ($akun->saldo_awal_kredit ?? 0);
                            $saldo_awal_debit = $saldo_awal > 0 ? $saldo_awal : 0;
                            $saldo_awal_kredit = $saldo_awal < 0 ? abs($saldo_awal) : 0;

                            $mutasi_debit = $akun->total_debit ?? 0;
                            $mutasi_kredit = $akun->total_kredit ?? 0;

                            $saldo_akhir = $saldo_awal + $mutasi_debit - $mutasi_kredit;
                            $saldo_akhir_debit = $saldo_akhir > 0 ? $saldo_akhir : 0;
                            $saldo_akhir_kredit = $saldo_akhir < 0 ? abs($saldo_akhir) : 0;

                            // Lewati baris jika tidak ada data
                            if (
                                $saldo_awal_debit == 0 &&
                                $saldo_awal_kredit == 0 &&
                                $mutasi_debit == 0 &&
                                $mutasi_kredit == 0
                            ) {
                                continue;
                            }

                            $data_tersedia = true;

                            $total_saldo_awal_debit += $saldo_awal_debit;
                            $total_saldo_awal_kredit += $saldo_awal_kredit;
                            $total_mutasi_debit += $mutasi_debit;
                            $total_mutasi_kredit += $mutasi_kredit;
                            $total_saldo_akhir_debit += $saldo_akhir_debit;
                            $total_saldo_akhir_kredit += $saldo_akhir_kredit;
                        @endphp

                        <tr>
                            <td>{{ $akun->kode_akun }}</td>
                            <td style="padding-left: {{ ($akun->level - 1) * 20 }}px;">{{ $akun->nama_akun }}</td>
                            <td class="text-right">
                                {{ $saldo_awal_debit > 0 ? number_format($saldo_awal_debit, 2, ',', '.') : '-' }}</td>
                            <td class="text-right">
                                {{ $saldo_awal_kredit > 0 ? number_format($saldo_awal_kredit, 2, ',', '.') : '-' }}</td>
                            <td class="text-right">
                                {{ $mutasi_debit > 0 ? number_format($mutasi_debit, 2, ',', '.') : '-' }}</td>
                            <td class="text-right">
                                {{ $mutasi_kredit > 0 ? number_format($mutasi_kredit, 2, ',', '.') : '-' }}</td>
                            <td class="text-right">
                                {{ $saldo_akhir_debit > 0 ? number_format($saldo_akhir_debit, 2, ',', '.') : '-' }}</td>
                            <td class="text-right">
                                {{ $saldo_akhir_kredit > 0 ? number_format($saldo_akhir_kredit, 2, ',', '.') : '-' }}</td>
                        </tr>
                    @endforeach

                    @if (!$data_tersedia)
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data transaksi atau saldo awal untuk
                                periode ini.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-light text-right font-weight-bold">
                    <tr>
                        <td colspan="2" class="text-center">TOTAL</td>
                        <td>{{ $total_saldo_awal_debit > 0 ? number_format($total_saldo_awal_debit, 2, ',', '.') : '-' }}
                        </td>
                        <td>{{ $total_saldo_awal_kredit > 0 ? number_format($total_saldo_awal_kredit, 2, ',', '.') : '-' }}
                        </td>
                        <td>{{ $total_mutasi_debit > 0 ? number_format($total_mutasi_debit, 2, ',', '.') : '-' }}</td>
                        <td>{{ $total_mutasi_kredit > 0 ? number_format($total_mutasi_kredit, 2, ',', '.') : '-' }}</td>
                        <td>{{ $total_saldo_akhir_debit > 0 ? number_format($total_saldo_akhir_debit, 2, ',', '.') : '-' }}
                        </td>
                        <td>{{ $total_saldo_akhir_kredit > 0 ? number_format($total_saldo_akhir_kredit, 2, ',', '.') : '-' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@push('js')
    <script>
        function toggleFilterFields() {
            const filterType = document.getElementById('filter_type').value;
            document.getElementById('bulanFilter').classList.toggle('d-none', filterType !== 'bulan');
            document.getElementById('periodeFilter').classList.toggle('d-none', filterType !== 'periode');
        }

        document.getElementById('filter_type').addEventListener('change', toggleFilterFields);
        document.addEventListener('DOMContentLoaded', toggleFilterFields);
    </script>
@endpush
