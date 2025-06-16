@extends('adminlte::page')

@section('title', 'Daftar Piutang')

@section('content_header')
    <h1 class="font-weight-bold">Daftar Piutang Customer</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <div class="btn-group" role="group" aria-label="Filter Piutang">
                        <button class="btn btn-outline-primary filter-btn active" data-status="semua">Semua</button>
                        <button class="btn btn-outline-danger filter-btn" data-status="belum_dibayar">Belum Dibayar</button>
                        <button class="btn btn-outline-warning filter-btn" data-status="partial">Partial</button>
                        <button class="btn btn-outline-success filter-btn" data-status="lunas">Lunas</button>
                    </div>
                </div>
                <div>
                    <a href="{{ route('piutang.exportExcel') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('piutang.exportPdf') }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            {{-- Table Piutang --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No. Invoice</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Umur Piutang</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($semuaFaktur as $invoice)
                            @php
                                $jatuhTempo = \Carbon\Carbon::parse($invoice->jatuh_tempo);
                                $hariIni = \Carbon\Carbon::today();
                                $diffHari = $hariIni->diffInDays($jatuhTempo, false);
                                $sudahJatuhTempo = $diffHari < 0;

                                if ($invoice->status !== 'lunas') {
                                    $umurPiutang = $sudahJatuhTempo
                                        ? abs($diffHari) . ' hari (lewat)'
                                        : $diffHari . ' hari';
                                    $umurClass = $sudahJatuhTempo ? 'text-danger' : 'text-success';
                                } else {
                                    $umurPiutang = '-';
                                    $umurClass = 'text-muted';
                                }
                            @endphp
                            <tr class="invoice-row status-{{ $invoice->status }}">
                                <td>{{ $invoice->nomor_invoice }}</td>
                                <td>{{ $invoice->nama_customer ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $jatuhTempo->format('d-m-Y') }}</td>
                                <td class="{{ $umurClass }}">{{ $umurPiutang }}</td>
                                <td class="text-end">Rp {{ number_format($invoice->total + $invoice->ppn, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($invoice->status === 'belum_dibayar')
                                        <span class="badge badge-danger">Belum Dibayar</span>
                                    @elseif ($invoice->status === 'partial')
                                        <span class="badge badge-warning">Partial</span>
                                    @else
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data faktur/piutang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @stop

        @section('js')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const buttons = document.querySelectorAll('.filter-btn');
                    const rows = document.querySelectorAll('.invoice-row');

                    buttons.forEach(button => {
                        button.addEventListener('click', () => {
                            buttons.forEach(btn => btn.classList.remove('active'));
                            button.classList.add('active');

                            const status = button.dataset.status;

                            rows.forEach(row => {
                                if (status === 'semua') {
                                    row.style.display = '';
                                } else {
                                    row.style.display = row.classList.contains('status-' + status) ?
                                        '' : 'none';
                                }
                            });
                        });
                    });
                });
            </script>
        @stop
