@extends('adminlte::page')

@section('title', 'Faktur Pajak Masukan')

@section('content_header')
    <h1 class="font-weight-bold">Daftar PPN Masukan</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="mb-3 row">
                <div class="col-md-3">
                    <input type="month" name="bulan" class="form-control" value="{{ request('bulan') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nomor Faktur Pajak</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>DPP</th>
                            <th>PPN (11%)</th>
                            <th>Total Faktur</th> {{-- Tambahan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalDpp = 0;
                            $totalPpn = 0;
                            $totalFaktur = 0;
                        @endphp
                        @forelse ($ppnMasukan as $key => $item)
                            @php
                                $dpp = $item->nilai_dpp;
                                $ppn = $item->nilai_ppn;
                                $total = $dpp + $ppn;

                                $totalDpp += $dpp;
                                $totalPpn += $ppn;
                                $totalFaktur += $total;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->nomor_faktur_pajak }}</td>
                                <td>{{ tanggal_indonesia($item->tanggal_faktur_pajak) }}</td>
                                <td>{{ $item->invoice->penerimaan->purchaseOrder->supplier->nama ?? '-' }}</td>
                                <td class="text-right">Rp {{ number_format($dpp, 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($ppn, 2, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data PPN Masukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @stop
