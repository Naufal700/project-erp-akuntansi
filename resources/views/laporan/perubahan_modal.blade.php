@extends('adminlte::page')

@section('title', 'Laporan Perubahan Modal')

@section('content_header')
    <h1>Laporan Perubahan Modal</h1>
@stop

@section('content')
    <form method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
                <x-adminlte-input name="tanggal_awal" label="Dari Tanggal" type="date" value="{{ $tanggal_awal }}" />
            </div>
            <div class="col-md-3">
                <x-adminlte-input name="tanggal_akhir" label="Sampai Tanggal" type="date" value="{{ $tanggal_akhir }}" />
            </div>
            <div class="col-md-2 align-self-end">
                <x-adminlte-button type="submit" label="Tampilkan" theme="primary" />
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>Modal Awal</th>
            <td class="text-end">{{ number_format($modalAwal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Setoran Modal Tambahan</th>
            <td class="text-end">{{ number_format($setoranModal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Laba Bersih</th>
            <td class="text-end">{{ number_format($labaBersih, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Prive</th>
            <td class="text-end">({{ number_format($prive, 2, ',', '.') }})</td>
        </tr>
        <tr class="bg-secondary text-white">
            <th>Modal Akhir</th>
            <th class="text-end">{{ number_format($modalAkhir, 2, ',', '.') }}</th>
        </tr>
    </table>
@stop
