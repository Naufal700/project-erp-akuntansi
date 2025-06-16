@extends('adminlte::page')

@section('title', 'Laporan Laba Rugi')

@section('content_header')
    <h1 class="text-bold">Laporan Laba Rugi</h1>
@stop

@section('content')
    <form method="GET" action="{{ route('laporan.laba-rugi') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="tanggal_awal" class="form-control"
                    value="{{ request('tanggal_awal') ?? date('Y-m-01') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal_akhir" class="form-control"
                    value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <strong>Laporan Laba Rugi</strong> <span class="float-right">{{ request('tanggal_awal') }} s/d
                {{ request('tanggal_akhir') }}</span>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><strong>Pendapatan</strong></td>
                        <td class="text-right">Rp {{ number_format($pendapatan, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Harga Pokok Penjualan (HPP)</td>
                        <td class="text-right">Rp {{ number_format($hpp, 2, ',', '.') }}</td>
                    </tr>
                    <tr class="table-secondary">
                        <td><strong>Laba Kotor</strong></td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($laba_kotor, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Beban Operasional</td>
                        <td class="text-right">Rp {{ number_format($beban, 2, ',', '.') }}</td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>Laba Bersih</strong></td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($laba_bersih, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop
