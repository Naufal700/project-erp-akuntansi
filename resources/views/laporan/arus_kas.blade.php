@extends('adminlte::page')

@section('title', 'Laporan Arus Kas')

@section('content_header')
    <h1 class="text-dark fw-bold">
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
                $totalMasuk = 0;
                $totalKeluar = 0;
            @endphp

            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Uraian</th>
                            <th class="text-end" style="width: 25%;">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelompokLabels as $key => $label)
                            @php
                                $dataKelompok = $arusKas[$key] ?? [];
                                $masuk = $totalArusKas[$key]['masuk'] ?? 0;
                                $keluar = $totalArusKas[$key]['keluar'] ?? 0;
                                $totalMasuk += $masuk;
                                $totalKeluar += $keluar;
                            @endphp

                            {{-- Judul Kelompok --}}
                            <tr class="fw-semibold bg-light">
                                <td colspan="2">{{ $label }}</td>
                            </tr>

                            {{-- Detail --}}
                            @foreach ($dataKelompok as $item)
                                <tr>
                                    <td>{{ $item['keterangan'] }}</td>
                                    <td class="text-end text">{{ formatCurrency($item['jumlah']) }}</td>
                                </tr>
                            @endforeach


                            {{-- Arus kas bersih dari kegiatan --}}
                            <tr class="fw-bold">
                                <td class="text-end text-bold">Arus kas bersih dari kegiatan {{ $label }}</td>
                                <td class="text-end text-bold">
                                    {{ formatCurrency($masuk - $keluar) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- Ringkasan --}}
                    <tfoot class="fw-bold">
                        <tr class="bg-light">
                            <td class="text-end text-bold">Kenaikan / Penurunan Kas</td>
                            <td class="text-end text-bold">{{ formatCurrency($totalMasuk - $totalKeluar) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end text-bold">Saldo Awal Kas</td>
                            <td class="text-end text-bold">{{ formatCurrency($saldoAwal) }}</td>
                        </tr>
                        <tr class="bg-light">
                            <td class="text-end text-bold">Saldo Akhir Kas</td>
                            <td class="text-end text-bold">
                                {{ formatCurrency($saldoAwal + $totalMasuk - $totalKeluar) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
