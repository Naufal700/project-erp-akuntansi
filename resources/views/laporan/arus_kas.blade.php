@extends('adminlte::page')

@section('title', 'Laporan Arus Kas')

@section('content_header')
    <h1>Laporan Arus Kas (Metode Langsung)</h1>
@stop

@section('content')
    <form method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
                <x-adminlte-input name="tanggal_awal" label="Dari Tanggal" type="date"
                    value="{{ request('tanggal_awal', $tanggal_awal) }}" />
            </div>
            <div class="col-md-3">
                <x-adminlte-input name="tanggal_akhir" label="Sampai Tanggal" type="date"
                    value="{{ request('tanggal_akhir', $tanggal_akhir) }}" />
            </div>
            <div class="col-md-2 align-self-end">
                <x-adminlte-button type="submit" label="Tampilkan" theme="primary" />
            </div>
        </div>
    </form>

    @php
        function tampilkanKelompok($data)
        {
            foreach ($data as $item) {
                echo '<tr>
                        <td>' .
                    date('d-m-Y', strtotime($item['tanggal'])) .
                    '</td>
                        <td>' .
                    $item['keterangan'] .
                    '</td>
                        <td class="text-end">' .
                    ($item['jenis'] === 'masuk' ? formatCurrency($item['jumlah']) : '-') .
                    '</td>
                        <td class="text-end">' .
                    ($item['jenis'] === 'keluar' ? formatCurrency($item['jumlah']) : '-') .
                    '</td>
                    </tr>';
            }
        }
    @endphp

    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th colspan="4">Arus Kas dari Aktivitas Operasi</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th class="text-end">Kas Masuk</th>
                <th class="text-end">Kas Keluar</th>
            </tr>
        </thead>
        <tbody>
            {!! tampilkanKelompok($arusKas['operasi']) !!}
            <tr class="fw-bold">
                <td colspan="2">Total Operasi</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['operasi']['masuk']) }}</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['operasi']['keluar']) }}</td>
            </tr>
        </tbody>

        <thead class="table-info">
            <tr>
                <th colspan="4">Arus Kas dari Aktivitas Investasi</th>
            </tr>
        </thead>
        <tbody>
            {!! tampilkanKelompok($arusKas['investasi']) !!}
            <tr class="fw-bold">
                <td colspan="2">Total Investasi</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['investasi']['masuk']) }}</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['investasi']['keluar']) }}</td>
            </tr>
        </tbody>

        <thead class="table-success">
            <tr>
                <th colspan="4">Arus Kas dari Aktivitas Pendanaan</th>
            </tr>
        </thead>
        <tbody>
            {!! tampilkanKelompok($arusKas['pendanaan']) !!}
            <tr class="fw-bold">
                <td colspan="2">Total Pendanaan</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['pendanaan']['masuk']) }}</td>
                <td class="text-end">{{ formatCurrency($totalArusKas['pendanaan']['keluar']) }}</td>
            </tr>
        </tbody>

        @php
            $totalMasuk =
                $totalArusKas['operasi']['masuk'] +
                $totalArusKas['investasi']['masuk'] +
                $totalArusKas['pendanaan']['masuk'];
            $totalKeluar =
                $totalArusKas['operasi']['keluar'] +
                $totalArusKas['investasi']['keluar'] +
                $totalArusKas['pendanaan']['keluar'];
            $totalKenaikanKas = $totalMasuk - $totalKeluar;
        @endphp

        <tfoot class="table-secondary fw-bold">
            <tr>
                <td colspan="2">Total Kenaikan/Penurunan Kas</td>
                <td colspan="2" class="text-end">{{ formatCurrency($totalKenaikanKas) }}</td>
            </tr>
        </tfoot>
    </table>
@stop
