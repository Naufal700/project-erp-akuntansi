@extends('adminlte::page')

@section('title', 'Laporan Arus Kas')

@section('content_header')
    <h1 class="text-dark">
        <i class="fas fa-file-invoice-dollar mr-1"></i> Laporan Arus Kas (Metode Langsung)
    </h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <x-adminlte-input name="tanggal_awal" label="Dari Tanggal" type="date"
                            value="{{ request('tanggal_awal', $tanggal_awal) }}" />
                    </div>
                    <div class="col-md-3">
                        <x-adminlte-input name="tanggal_akhir" label="Sampai Tanggal" type="date"
                            value="{{ request('tanggal_akhir', $tanggal_akhir) }}" />
                    </div>
                    <div class="col-md-2 align-self-end">
                        <x-adminlte-button type="submit" label="Tampilkan" theme="dark" icon="fas fa-search" />
                    </div>
                </div>
            </form>

            @php
                $kelompokLabels = [
                    'operasi' => 'Aktivitas Operasi',
                    'investasi' => 'Aktivitas Investasi',
                    'pendanaan' => 'Aktivitas Pendanaan',
                ];

                function tampilkanKelompok($data)
                {
                    $html = '';
                    foreach ($data as $item) {
                        $html .=
                            '<tr>
                    <td>' .
                            date('d-m-Y', strtotime($item['tanggal'])) .
                            '</td>
                    <td>' .
                            e($item['keterangan']) .
                            '</td>
                    <td class="text-end">' .
                            ($item['jenis'] === 'masuk' ? formatCurrency($item['jumlah']) : '-') .
                            '</td>
                    <td class="text-end">' .
                            ($item['jenis'] === 'keluar' ? formatCurrency($item['jumlah']) : '-') .
                            '</td>
                </tr>';
                    }
                    return $html;
                }

                $totalMasuk = 0;
                $totalKeluar = 0;
            @endphp
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-dark text-center">
                            @foreach ($kelompokLabels as $key => $label)
                                @php
                                    $masuk = $totalArusKas[$key]['masuk'] ?? 0;
                                    $keluar = $totalArusKas[$key]['keluar'] ?? 0;
                                    $totalMasuk += $masuk;
                                    $totalKeluar += $keluar;
                                @endphp

                                <thead class="bg-light">
                                    <tr>
                                        <th colspan="4" class="fw-semibold">{{ $label }}</th>
                                    </tr>
                                    <tr class="bg-white">
                                        <th style="width: 15%;">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="text-end" style="width: 15%;">Kas Masuk</th>
                                        <th class="text-end" style="width: 15%;">Kas Keluar</th>
                                    </tr>
                                </thead>
                        <tbody>
                            {!! tampilkanKelompok($arusKas[$key] ?? []) !!}
                            <tr class="fw-bold bg-light">
                                <td colspan="2">Total {{ $label }}</td>
                                <td class="text-end">{{ formatCurrency($masuk) }}</td>
                                <td class="text-end">{{ formatCurrency($keluar) }}</td>
                            </tr>
                        </tbody>
                        @endforeach

                        <tfoot class="bg-secondary text-white fw-bold">
                            <tr>
                                <td colspan="2">Total Kenaikan / Penurunan Kas</td>
                                <td colspan="2" class="text-end">{{ formatCurrency($totalMasuk - $totalKeluar) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @stop
