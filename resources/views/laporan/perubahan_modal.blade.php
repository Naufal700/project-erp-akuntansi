@extends('adminlte::page')

@section('title', 'Laporan Perubahan Modal')

@section('content_header')
    <h1 class="fw-semibold text-dark">
        <i class="fas fa-chart-line me-1"></i> Laporan Perubahan Modal
    </h1>
@stop

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Form Filter --}}
            <form method="GET" class="mb-4">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir }}">
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-filter me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Header Periode --}}
            <div class="mb-3">
                <strong>Periode:</strong> {{ $tanggal_awal }} s/d {{ $tanggal_akhir }}
            </div>

            {{-- Tabel Perubahan Modal --}}
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <tbody>
                        <tr>
                            <td>Modal Awal</td>
                            <td class="text-end">Rp {{ number_format($modalAwal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Setoran Modal Tambahan</td>
                            <td class="text-end">Rp {{ number_format($setoranModal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Laba Bersih Periode Ini</td>
                            <td class="text-end">Rp {{ number_format($labaBersih, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Prive</td>
                            <td class="text-end">(Rp {{ number_format($prive, 2, ',', '.') }})</td>
                        </tr>
                        <tr class="table-light fw-bold">
                            <td>Modal Akhir</td>
                            <td class="text-end">Rp {{ number_format($modalAkhir, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
