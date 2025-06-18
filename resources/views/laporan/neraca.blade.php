@extends('adminlte::page')

@section('title', 'Laporan Neraca')

@section('content_header')
    <h1 class="text-dark fw-semibold mb-2">
        <i class="fas fa-balance-scale"></i> Laporan Posisi Keuangan (Neraca)
    </h1>
@stop
@section('css')
    <style>
        .table td {
            padding: 0.4rem 0.5rem;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
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
                    <div class="col-md-6 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-filter me-1"></i> Tampilkan
                        </button>
                        <a href="{{ route('laporan.neraca.export', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}"
                            class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <strong class="text-dark">Posisi Keuangan</strong>
                    @if (request('tanggal_awal'))
                        <span class="text-muted">
                            ({{ \Carbon\Carbon::parse(request('tanggal_awal'))->translatedFormat('d F Y') }}
                            – {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->translatedFormat('d F Y') }})
                        </span>
                    @endif
                </div>
                <div class="card-body row">
                    {{-- KOLOM ASET --}}
                    <div class="card-body">
                        <div class="row">
                            {{-- KOLOM ASET --}}
                            <div class="col-md-6 pe-md-4">
                                <h5 class="fw-semibold text-uppercase border-bottom pb-1">ASET</h5>

                                {{-- Aset Lancar --}}
                                <h6 class="text-muted fw-semibold mt-3">Aset Lancar</h6>
                                <table class="table table-sm table-borderless">
                                    @foreach ($aset_lancar as $item)
                                        <tr>
                                            <td>{{ $item['nama_akun'] }}</td>
                                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-semibold">
                                        <td>Subtotal Aset Lancar</td>
                                        <td class="text-end">Rp {{ number_format(abs($sub_aset_lancar), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>

                                {{-- Aset Tetap --}}
                                <h6 class="text-muted fw-semibold mt-3">Aset Tetap</h6>
                                <table class="table table-sm table-borderless">
                                    @foreach ($aset_tetap as $item)
                                        <tr>
                                            <td>{{ $item['nama_akun'] }}</td>
                                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-semibold">
                                        <td>Subtotal Aset Tetap</td>
                                        <td class="text-end">Rp {{ number_format(abs($sub_aset_tetap), 2, ',', '.') }}</td>
                                    </tr>
                                </table>

                                <div class="fw-bold text-end mt-3 border-top pt-2">
                                    Total Aset: Rp {{ number_format(abs($total_aset), 2, ',', '.') }}
                                </div>
                            </div>

                            {{-- KOLOM KEWAJIBAN & EKUITAS --}}
                            <div class="col-md-6 ps-md-4 border-start">
                                <h5 class="fw-semibold text-uppercase border-bottom pb-1">KEWAJIBAN & EKUITAS</h5>

                                {{-- Kewajiban Jangka Pendek --}}
                                <h6 class="text-muted fw-semibold mt-3">Kewajiban Jangka Pendek</h6>
                                <table class="table table-sm table-borderless">
                                    @foreach ($kewajiban_jp as $item)
                                        <tr>
                                            <td>{{ $item['nama_akun'] }}</td>
                                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-semibold">
                                        <td>Subtotal Kewajiban Jangka Pendek</td>
                                        <td class="text-end">Rp {{ number_format(abs($sub_kewajiban_jp), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>

                                {{-- Kewajiban Jangka Panjang --}}
                                <h6 class="text-muted fw-semibold mt-3">Kewajiban Jangka Panjang</h6>
                                <table class="table table-sm table-borderless">
                                    @foreach ($kewajiban_pj as $item)
                                        <tr>
                                            <td>{{ $item['nama_akun'] }}</td>
                                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-semibold">
                                        <td>Subtotal Kewajiban Jangka Panjang</td>
                                        <td class="text-end">Rp {{ number_format(abs($sub_kewajiban_pj), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                </table>

                                {{-- Ekuitas --}}
                                <h6 class="text-muted fw-semibold mt-3">Ekuitas</h6>
                                <table class="table table-sm table-borderless">
                                    @foreach ($modal as $item)
                                        <tr>
                                            <td>{{ $item['nama_akun'] }}</td>
                                            <td class="text-end">Rp {{ number_format(abs($item['saldo']), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Laba Ditahan Tahun Lalu</td>
                                        <td class="text-end">Rp {{ number_format(abs($laba_ditahan ?? 0), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Laba Bersih Berjalan</td>
                                        <td class="text-end">Rp {{ number_format(abs($laba_berjalan ?? 0), 2, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr class="fw-semibold">
                                        <td>Subtotal Ekuitas</td>
                                        <td class="text-end">Rp {{ number_format(abs($total_modal), 2, ',', '.') }}</td>
                                    </tr>
                                </table>

                                <div class="fw-bold text-end mt-3 border-top pt-2">
                                    Total Kewajiban & Ekuitas: Rp {{ number_format(abs($total_passiva), 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stop
