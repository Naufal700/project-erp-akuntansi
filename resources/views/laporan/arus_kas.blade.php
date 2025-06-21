@extends('adminlte::page')

@section('title', 'Laporan Arus Kas')

@section('content_header')
    <h1 class="fw-semibold text-dark">
        <i class="fas fa-file-invoice-dollar me-1"></i> Laporan Arus Kas (Metode Langsung)
    </h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Form Filter Tanggal --}}
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal', $tanggal_awal) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir', $tanggal_akhir) }}">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Data Arus Kas --}}
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

                            {{-- Detail Transaksi --}}
                            @foreach ($dataKelompok as $item)
                                <tr>
                                    <td class="ps-4">{{ $item['keterangan'] }}</td>
                                    <td class="text-end">{{ formatCurrency($item['jumlah']) }}</td>
                                </tr>
                            @endforeach

                            {{-- Subtotal Kelompok --}}
                            <tr class="fw-bold">
                                <td class="text-end">Arus kas bersih dari {{ strtolower($label) }}</td>
                                <td class="text-end">{{ formatCurrency($masuk - $keluar) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- Ringkasan Akhir --}}
                    <tfoot class="fw-bold">
                        <tr class="bg-light">
                            <td class="text-end">Kenaikan / Penurunan Kas</td>
                            <td class="text-end">{{ formatCurrency($totalMasuk - $totalKeluar) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end">Saldo Awal Kas</td>
                            <td class="text-end">{{ formatCurrency($saldoAwal) }}</td>
                        </tr>
                        <tr class="bg-light">
                            <td class="text-end">Saldo Akhir Kas</td>
                            <td class="text-end">
                                {{ formatCurrency($saldoAwal + $totalMasuk - $totalKeluar) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
