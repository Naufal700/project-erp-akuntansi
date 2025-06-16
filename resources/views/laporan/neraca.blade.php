@extends('adminlte::page')

@section('title', 'Laporan Neraca')

@section('content_header')
    <h1 class="text-bold">Laporan Posisi Keuangan (Neraca)</h1>
@stop

@section('content')
    <form method="GET" action="{{ route('laporan.neraca') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control"
                    value="{{ request('tanggal_awal') ?? date('Y-m-01') }}">
            </div>
            <div class="col-md-3">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control"
                    value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
            </div>
            <div class="col-md-6 d-flex gap-2">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
                <div>
                    <a href="{{ route('laporan.neraca.export', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
                <div>
                    <a href="{{ route('laporan.neraca.closing', ['tanggal_akhir' => request('tanggal_akhir')]) }}"
                        class="btn btn-danger" onclick="return confirm('Yakin ingin melakukan closing bulan ini?')">
                        <i class="fas fa-lock"></i> Closing Bulan Ini
                    </a>
                </div>
                @if ($sudahClosing ?? false)
                    <div>
                        <a href="{{ route('laporan.neraca.batalClosing', ['tanggal_akhir' => request('tanggal_akhir')]) }}"
                            class="btn btn-warning" onclick="return confirm('Batalkan closing bulan ini?')">
                            <i class="fas fa-unlock"></i> Batalkan Closing
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow">
        <div class="card-header bg-primary text-white fw-bold">
            Posisi Keuangan
            {{ request('tanggal_awal') ? 'Periode ' . \Carbon\Carbon::parse(request('tanggal_awal'))->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse(request('tanggal_akhir'))->translatedFormat('d F Y') : '' }}
        </div>
        <div class="card-body row">
            {{-- =================== KOLOM ASET =================== --}}
            <div class="col-md-6">
                <h5 class="fw-bold text-uppercase mb-3">Aset</h5>

                {{-- Aset Lancar --}}
                @include('partials.neraca_section', [
                    'title' => 'Aset Lancar',
                    'data' => $aset_lancar,
                    'color' => 'primary',
                    'subtotal' => 'sub_aset_lancar',
                ])

                {{-- Aset Tetap --}}
                @include('partials.neraca_section', [
                    'title' => 'Aset Tetap',
                    'data' => $aset_tetap,
                    'color' => 'primary',
                    'subtotal' => 'sub_aset_tetap',
                ])

                <div class="alert alert-info fw-bold">
                    Total Aset: Rp {{ number_format(abs($total_aset), 2, ',', '.') }}
                </div>
            </div>

            {{-- =================== KOLOM KEWAJIBAN & EKUITAS =================== --}}
            <div class="col-md-6">
                <h5 class="fw-bold text-uppercase mb-3">Kewajiban & Ekuitas</h5>

                {{-- Kewajiban Jangka Pendek --}}
                @include('partials.neraca_section', [
                    'title' => 'Kewajiban Jangka Pendek',
                    'data' => $kewajiban_jp,
                    'color' => 'danger',
                    'subtotal' => 'sub_kewajiban_jp',
                ])

                {{-- Kewajiban Jangka Panjang --}}
                @include('partials.neraca_section', [
                    'title' => 'Kewajiban Jangka Panjang',
                    'data' => $kewajiban_pj,
                    'color' => 'danger',
                    'subtotal' => 'sub_kewajiban_pj',
                ])

                {{-- Ekuitas --}}
                <h6 class="fw-bold text-success">Ekuitas</h6>
                <table class="table table-sm table-striped">
                    @php $total_modal = 0; @endphp
                    @foreach ($modal as $m)
                        @if ($m['saldo'] != 0)
                            <tr>
                                <td>{{ $m['nama_akun'] }}</td>
                                <td class="text-end">Rp {{ number_format(abs($m['saldo']), 2, ',', '.') }}</td>
                            </tr>
                            @php $total_modal += $m['saldo']; @endphp
                        @endif
                    @endforeach
                    <tr>
                        <td>Laba Ditahan Tahun Lalu</td>
                        <td class="text-end">Rp {{ number_format(abs($laba_ditahan ?? 0), 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Laba Bersih Berjalan</td>
                        <td class="text-end">Rp {{ number_format(abs($laba_berjalan ?? 0), 2, ',', '.') }}</td>
                    </tr>
                    @php $total_modal += ($laba_ditahan ?? 0) + ($laba_berjalan ?? 0); @endphp
                    <tr class="table-light fw-bold">
                        <td>Subtotal Ekuitas</td>
                        <td class="text-end">Rp {{ number_format(abs($total_modal), 2, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="alert alert-info fw-bold">
                    Total Kewajiban & Ekuitas: Rp {{ number_format(abs($total_passiva), 2, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
@stop
