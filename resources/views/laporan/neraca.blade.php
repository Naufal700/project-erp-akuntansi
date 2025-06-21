@extends('adminlte::page')

@section('title', 'Laporan Posisi Keuangan')

@section('css')
    <style>
        .section-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #343a40;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1rem;
            padding-bottom: 0.25rem;
        }

        .table-financial td {
            padding: 0.4rem 0.6rem;
            font-size: 0.95rem;
        }

        .table-financial .total-row {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .total-final {
            font-size: 1.1rem;
            font-weight: 700;
            color: #212529;
            border-top: 2px solid #adb5bd;
            padding-top: 0.5rem;
            margin-top: 1rem;
        }

        .text-money {
            text-align: right;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content_header')
    <h1 class="text-dark fw-semibold mb-2">
        <i class="fas fa-balance-scale me-1"></i> Laporan Posisi Keuangan (Neraca)
    </h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" action="{{ route('laporan.neraca') }}" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal') ?? date('Y-m-01') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir') ?? date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-filter me-1"></i> Tampilkan
                        </button>
                        <a href="{{ route('laporan.neraca.export', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}"
                            class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </a>
                        <a href="{{ route('laporan.neraca.pdf', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}"
                            class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                        <a href="{{ route('laporan.neraca.closing', ['tanggal_akhir' => request('tanggal_akhir')]) }}"
                            class="btn btn-outline-dark"
                            onclick="return confirm('Yakin ingin melakukan closing bulan ini?')">
                            <i class="fas fa-lock me-1"></i> Closing
                        </a>
                        @if ($sudahClosing ?? false)
                            <a href="{{ route('laporan.neraca.batalClosing', ['tanggal_akhir' => request('tanggal_akhir')]) }}"
                                class="btn btn-outline-warning" onclick="return confirm('Batalkan closing bulan ini?')">
                                <i class="fas fa-unlock me-1"></i> Batal Closing
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Header Periode --}}
            <div class="mb-3">
                <strong>Periode:</strong>
                {{ \Carbon\Carbon::parse(request('tanggal_awal'))->translatedFormat('d F Y') }}
                – {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->translatedFormat('d F Y') }}
            </div>

            {{-- Posisi Keuangan --}}
            <div class="row">
                {{-- Aktiva --}}
                <div class="col-md-6 pe-md-4">
                    <div class="section-title">ASET</div>

                    {{-- Aset Lancar --}}
                    <h6 class="text-muted fw-semibold">Aset Lancar</h6>
                    <table class="table table-sm table-borderless table-financial">
                        @foreach ($aset_lancar as $item)
                            <tr>
                                <td>{{ $item['nama_akun'] }}</td>
                                <td class="text-money">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>Subtotal Aset Lancar</td>
                            <td class="text-money">Rp {{ number_format(abs($sub_aset_lancar), 2, ',', '.') }}</td>
                        </tr>
                    </table>

                    {{-- Aset Tetap --}}
                    <h6 class="text-muted fw-semibold">Aset Tetap</h6>
                    <table class="table table-sm table-borderless table-financial">
                        @foreach ($aset_tetap as $item)
                            <tr>
                                <td>{{ $item['nama_akun'] }}</td>
                                <td class="text-money">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>Subtotal Aset Tetap</td>
                            <td class="text-money">Rp {{ number_format(abs($sub_aset_tetap), 2, ',', '.') }}</td>
                        </tr>
                    </table>

                    <div class="total-final text-end">
                        Total Aset: Rp {{ number_format(abs($total_aset), 2, ',', '.') }}
                    </div>
                </div>

                {{-- Kewajiban & Ekuitas --}}
                <div class="col-md-6 ps-md-4 border-start">
                    <div class="section-title">KEWAJIBAN & EKUITAS</div>

                    {{-- Kewajiban JP --}}
                    <h6 class="text-muted fw-semibold">Kewajiban Jangka Pendek</h6>
                    <table class="table table-sm table-borderless table-financial">
                        @foreach ($kewajiban_jp as $item)
                            <tr>
                                <td>{{ $item['nama_akun'] }}</td>
                                <td class="text-money">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>Subtotal Kewajiban Jangka Pendek</td>
                            <td class="text-money">Rp {{ number_format(abs($sub_kewajiban_jp), 2, ',', '.') }}</td>
                        </tr>
                    </table>

                    {{-- Kewajiban PJ --}}
                    <h6 class="text-muted fw-semibold">Kewajiban Jangka Panjang</h6>
                    <table class="table table-sm table-borderless table-financial">
                        @foreach ($kewajiban_pj as $item)
                            <tr>
                                <td>{{ $item['nama_akun'] }}</td>
                                <td class="text-money">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>Subtotal Kewajiban Jangka Panjang</td>
                            <td class="text-money">Rp {{ number_format(abs($sub_kewajiban_pj), 2, ',', '.') }}</td>
                        </tr>
                    </table>

                    {{-- Ekuitas --}}
                    <h6 class="text-muted fw-semibold">Ekuitas</h6>
                    <table class="table table-sm table-borderless table-financial">
                        @foreach ($modal as $item)
                            <tr>
                                <td>{{ $item['nama_akun'] }}</td>
                                <td class="text-money">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>Laba Ditahan Tahun Lalu</td>
                            <td class="text-money">Rp {{ number_format(abs($laba_ditahan ?? 0), 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Laba Bersih Berjalan</td>
                            <td class="text-money">Rp {{ number_format(abs($laba_berjalan ?? 0), 2, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>Subtotal Ekuitas</td>
                            <td class="text-money">Rp {{ number_format(abs($total_modal), 2, ',', '.') }}</td>
                        </tr>
                    </table>

                    <div class="total-final text-end">
                        Total Kewajiban & Ekuitas: Rp {{ number_format(abs($total_passiva), 2, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Cek Keseimbangan --}}
            @if ($total_aset !== $total_passiva)
                <div class="alert alert-danger mt-4">
                    <i class="fas fa-exclamation-circle me-1"></i> Total Aktiva dan Pasiva tidak seimbang!
                </div>
            @endif
        </div>
    </div>
@stop
