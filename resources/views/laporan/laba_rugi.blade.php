@extends('adminlte::page')

@section('title', 'Laporan Laba Rugi')

@section('content_header')
    <h1 class="fw-semibold text-dark">
        <i class="fas fa-file-invoice-dollar me-1"></i> Laporan Laba Rugi
    </h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Form Filter Tanggal --}}
            <form method="GET" action="{{ route('laporan.laba-rugi') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label for="tanggal_awal" class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal') ?? date('Y-m-01') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-filter me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tabel Laba Rugi --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th colspan="2" class="text-center">
                                Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Pendapatan --}}
                        <tr class="fw-semibold bg-light">
                            <td colspan="2">Pendapatan</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Penjualan</td>
                            <td class="text-end">Rp {{ number_format($pendapatan, 2, ',', '.') }}</td>
                        </tr>

                        {{-- HPP --}}
                        <tr class="fw-semibold bg-light">
                            <td colspan="2">Harga Pokok Penjualan</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Total HPP</td>
                            <td class="text-end">Rp {{ number_format($hpp, 2, ',', '.') }}</td>
                        </tr>

                        {{-- Laba Kotor --}}
                        <tr class="fw-bold table-light">
                            <td>Laba Kotor</td>
                            <td class="text-end">Rp {{ number_format($laba_kotor, 2, ',', '.') }}</td>
                        </tr>

                        {{-- Beban Operasional --}}
                        <tr class="fw-semibold bg-light">
                            <td colspan="2">Beban Operasional</td>
                        </tr>
                        <tr>
                            <td class="ps-4">Total Beban</td>
                            <td class="text-end">Rp {{ number_format($beban, 2, ',', '.') }}</td>
                        </tr>

                        {{-- Laba Bersih --}}
                        <tr class="fw-bold table-light">
                            <td>Laba Bersih</td>
                            <td class="text-end">Rp {{ number_format($laba_bersih, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
