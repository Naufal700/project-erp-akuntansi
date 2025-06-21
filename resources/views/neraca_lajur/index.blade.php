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

            @if ($data->isEmpty())
                <div class="alert alert-info">
                    Tidak ada data transaksi atau saldo awal untuk periode ini.
                </div>
            @else
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-hover text-nowrap align-middle">
                        <thead class="thead-dark sticky-top text-center">
                            <tr>
                                <th rowspan="2">Kode Akun</th>
                                <th rowspan="2">Nama Akun</th>
                                <th colspan="2">Saldo Awal</th>
                                <th colspan="2">Mutasi</th>
                                <th colspan="2">Neraca Saldo</th>
                                <th colspan="2">Penyesuaian</th>
                                <th colspan="2">Disesuaikan</th>
                                <th colspan="2">Laba Rugi</th>
                                <th colspan="2">Neraca</th>
                            </tr>
                            <tr>
                                @for ($i = 0; $i < 7; $i++)
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kolomList = [
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
                                ];

                                $total = array_fill_keys($kolomList, 0);
                            @endphp

                            @foreach ($data as $akun)
                                <tr>
                                    <td>{{ $akun['kode_akun'] }}</td>
                                    <td style="padding-left: {{ $akun['level'] * 15 }}px">{{ $akun['nama_akun'] }}</td>
                                    @foreach ($kolomList as $kolom)
                                        @php
                                            $nilai = (float) $akun[$kolom];
                                            $total[$kolom] += $nilai;
                                        @endphp
                                        <td class="text-end">
                                            {{ $nilai != 0 ? number_format($nilai, 0, ',', '.') : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary fw-bold text-end">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL</td>
                                @foreach ($kolomList as $kolom)
                                    <td>
                                        {{ $total[$kolom] != 0 ? number_format($total[$kolom], 0, ',', '.') : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop
