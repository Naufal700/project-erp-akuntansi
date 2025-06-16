@extends('adminlte::page')

@section('title', 'PPN Keluaran')

@section('content_header')
    <h1 class="font-weight-bold">Daftar PPN Keluaran</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('ppn.keluaran.exportExcel') }}" class="btn btn-sm btn-success mr-2">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('ppn.keluaran.exportPDF') }}" target="_blank" class="btn btn-sm btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th style="width: 120px;">Tanggal</th>
                                <th>Nomor Faktur</th>
                                <th>Customer</th>
                                <th class="text-end">DPP</th>
                                <th class="text-end">PPN (11%)</th>
                                <th class="text-end">Total Faktur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $item)
                                <tr>
                                    <td class="text-center align-middle">{{ $index + 1 }}</td>
                                    <td>{{ tanggal_indonesia($item['tanggal']) }}</td>
                                    <td class="align-middle">{{ $item['nomor_faktur'] }}</td>
                                    <td class="align-middle">{{ $item['customer'] }}</td>
                                    <td class="text-end align-middle">Rp {{ number_format($item['dpp'], 2, ',', '.') }}</td>
                                    <td class="text-end align-middle">Rp {{ number_format($item['ppn'], 2, ',', '.') }}</td>
                                    <td class="text-end align-middle">Rp {{ number_format($item['total'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Tidak ada data faktur tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @stop
