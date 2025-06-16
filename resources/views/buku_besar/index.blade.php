@extends('adminlte::page')

@section('title', 'Buku Besar')

@section('content_header')
    <h1 class="mb-4 text-primary font-weight-bold">Buku Besar</h1>
@stop

@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-body">

            <form method="GET" action="{{ url()->current() }}" class="mb-4" id="filterForm">
                <div class="form-row align-items-end">

                    <div class="form-group col-md-3">
                        <label for="akun" class="font-weight-bold text-secondary">Pilih Akun:</label>
                        <select name="akun" id="akun" class="form-control"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="">-- Semua Akun --</option>
                            @foreach ($all_coa as $coa)
                                <option value="{{ $coa->kode_akun }}"
                                    {{ request('akun') == $coa->kode_akun ? 'selected' : '' }}>
                                    {{ $coa->kode_akun }} - {{ $coa->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tanggal_awal" class="font-weight-bold text-secondary">Tanggal Awal:</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                            value="{{ $tanggal_awal }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tanggal_akhir" class="font-weight-bold text-secondary">Tanggal Akhir:</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                            value="{{ $tanggal_akhir }}">
                    </div>

                    <div class="form-group col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary mr-2 flex-grow-1 shadow-sm">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary shadow-sm" data-toggle="tooltip"
                            title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>

                </div>
            </form>

            <div class="mb-3 d-flex justify-content-end">
                <a href="{{ route('buku_besar.export_excel', request()->all()) }}" class="btn btn-success mr-2 shadow-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('buku_besar.export_pdf', request()->all()) }}" class="btn btn-danger shadow-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
                <table class="table table-bordered table-hover table-sm mb-0" style="min-width: 900px;">
                    <thead class="thead-dark sticky-top" style="top: 0; z-index: 1020;">
                        <tr>
                            <th style="min-width: 110px;">Tanggal</th>
                            <th style="min-width: 180px;">Akun</th>
                            <th style="min-width: 80px;">Ref</th>
                            <th>Keterangan</th>
                            <th class="text-right" style="min-width: 120px;">Debit</th>
                            <th class="text-right" style="min-width: 120px;">Kredit</th>
                            <th class="text-right" style="min-width: 120px;">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $grand_total_debit = 0;
                            $grand_total_kredit = 0;
                            $total_saldo_akhir = 0; // saldo akhir semua akun
                        @endphp

                        @foreach ($data as $item)
                            {{-- Header per akun --}}
                            <tr class="bg-secondary text-white font-weight-bold">
                                <td colspan="7">
                                    Akun: {{ $item['coa']->kode_akun }} - {{ $item['coa']->nama_akun }}
                                </td>
                            </tr>

                            {{-- Saldo awal --}}
                            @if (abs($item['saldo_awal']) > 0)
                                <tr class="bg-light font-weight-bold">
                                    <td></td>
                                    <td></td>
                                    <td>Saldo Awal</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($item['saldo_awal'], 2, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            @endif

                            @php
                                $saldo_running = $item['saldo_awal'];
                            @endphp

                            @forelse ($item['jurnal'] as $jurnal)
                                @php
                                    $saldo_running += $jurnal->nominal_debit - $jurnal->nominal_kredit;
                                    $grand_total_debit += $jurnal->nominal_debit;
                                    $grand_total_kredit += $jurnal->nominal_kredit;
                                @endphp
                                <tr style="transition: background-color 0.2s;">
                                    <td>{{ tanggal_indonesia($jurnal->tanggal) }}</td>
                                    <td>{{ $item['coa']->kode_akun }} - {{ $item['coa']->nama_akun }}</td>
                                    <td>{{ $jurnal->ref }}</td>
                                    <td>{{ $jurnal->keterangan }}</td>
                                    <td class="text-right text-dark">
                                        {{ number_format($jurnal->nominal_debit, 2, ',', '.') }}</td>
                                    <td class="text-right text-dark">
                                        {{ number_format($jurnal->nominal_kredit, 2, ',', '.') }}</td>
                                    <td class="text-right text-dark">{{ number_format($saldo_running, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td>{{ $item['coa']->kode_akun }} - {{ $item['coa']->nama_akun }}</td>
                                    <td colspan="6" class="text-center text-muted font-italic">Tidak ada transaksi pada
                                        periode ini.</td>
                                </tr>
                            @endforelse

                            @php
                                $total_saldo_akhir += $saldo_running; // akumulasi saldo akhir
                            @endphp
                        @endforeach

                        {{-- Total keseluruhan debit & kredit --}}
                        <tr class="bg-light font-weight-bold">
                            <td colspan="4" class="text-right">Total Keseluruhan</td>
                            <td class="text-right text-dark">{{ number_format($grand_total_debit, 2, ',', '.') }}</td>
                            <td class="text-right text-dark">{{ number_format($grand_total_kredit, 2, ',', '.') }}</td>
                            <td class="text-right text-dark">{{ number_format($total_saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $data->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Sticky header warna */
        .thead-primary th {
            background-color: #004085 !important;
            color: #fff !important;
        }

        /* Hover effect pada baris transaksi */
        .transaksi-row:hover {
            background-color: #d1ecf1 !important;
            transition: background-color 0.3s ease;
        }

        /* Tooltip styling */
        [data-toggle="tooltip"] {
            cursor: pointer;
        }

        /* Scrollbar styling untuk table responsive */
        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        })
    </script>
@stop
