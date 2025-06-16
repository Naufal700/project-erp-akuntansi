@extends('adminlte::page')

@section('title', 'Daftar Hutang')

@section('content_header')
    <h1 class="font-weight-bold">Daftar Hutang Supplier</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <div class="btn-group" role="group" aria-label="Filter Hutang">
                        <button class="btn btn-outline-primary filter-btn active" data-status="semua">Semua</button>
                        <button class="btn btn-outline-danger filter-btn" data-status="belum_dibayar">Belum Dibayar</button>
                        <button class="btn btn-outline-warning filter-btn" data-status="dicicil">Partial</button>
                        <button class="btn btn-outline-success filter-btn" data-status="lunas">Lunas</button>
                    </div>
                </div>
                <div>
                    <a href="{{ route('hutang-supplier.exportExcel') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('hutang-supplier.exportPdf') }}" class="btn btn-danger btn-sm" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Umur Hutang</th>
                            <th>No. Invoice</th>
                            <th>No. Kontrabon</th>
                            <th>Supplier</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            @php
                                $tgl = \Carbon\Carbon::parse($invoice['tanggal']);
                                $tempo = isset($invoice['jatuh_tempo'])
                                    ? \Carbon\Carbon::parse($invoice['jatuh_tempo'])
                                    : $tgl;
                                $today = \Carbon\Carbon::today();
                                $diff = $today->diffInDays($tempo, false);
                                $overdue = $diff < 0;

                                $dibayar = $invoice['dibayar'] ?? 0;
                                $sisa = $invoice['total'] - $dibayar;

                                if ($sisa == 0) {
                                    $finalStatus = 'lunas';
                                } elseif ($dibayar > 0) {
                                    $finalStatus = 'dicicil';
                                } else {
                                    $finalStatus = 'belum_dibayar';
                                }

                                if ($finalStatus === 'lunas' && $overdue) {
                                    $umur = '';
                                } elseif ($finalStatus === 'lunas') {
                                    $umur = '-';
                                } else {
                                    $umur = $overdue ? abs($diff) . ' hari (lewat)' : $diff . ' hari';
                                }

                                $umurClass =
                                    $finalStatus === 'lunas'
                                        ? 'text-muted'
                                        : ($overdue
                                            ? 'text-danger'
                                            : 'text-success');
                            @endphp

                            <tr class="invoice-row status-{{ $finalStatus }}">
                                <td>{{ tanggal_indonesia($tgl) }}</td>
                                <td>{{ tanggal_indonesia($tempo) }}</td>
                                <td class="{{ $umurClass }}">{{ $umur }}</td>
                                <td>{{ $invoice['nomor_invoice'] }}</td>
                                <td>{{ $invoice['nomor_kontrabon'] ?? 'Belum Kontrabon' }}</td>
                                <td>
                                    {{ $invoice['supplier'] === '-' ? 'Supplier tidak ditemukan' : $invoice['supplier'] }}
                                </td>
                                <td class="text-end">Rp {{ number_format($invoice['total'], 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($invoice['dibayar'], 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                <td>
                                    @if ($finalStatus === 'belum_dibayar')
                                        <span class="badge badge-danger">Belum Dibayar</span>
                                    @elseif ($finalStatus === 'dicicil')
                                        <span class="badge badge-warning">Partial</span>
                                    @else
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Tidak ada data hutang.</td>
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
                                const visible = status === 'semua' || row.classList.contains(
                                    'status-' + status);
                                row.style.display = visible ? '' : 'none';
                            });
                        });
                    });
                });
            </script>
        @stop
