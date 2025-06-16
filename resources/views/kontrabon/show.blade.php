@extends('adminlte::page')

@section('title', 'Detail Kontrabon')

@section('content_header')
    <h1 class="font-weight-bold">Detail Kontrabon</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Header Informasi Kontrabon --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%">Nomor Kontrabon</th>
                            <td>: {{ $kontrabon->nomor_kontrabon }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal</th>
                            <td>: {{ tanggal_indonesia($kontrabon->tanggal) }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>:
                                <span class="badge {{ $kontrabon->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $kontrabon->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Total</th>
                            <td>: Rp {{ number_format($kontrabon->total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <th class="text-muted" style="width: 40%">Supplier</th>
                            <td>: {{ $kontrabon->supplier->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Keterangan</th>
                            <td>: {{ $kontrabon->keterangan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            {{-- Detail Invoice --}}
            <h5 class="mb-3">Rincian Invoice</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kontrabon->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->invoice->nomor_invoice ?? '-' }}</td>
                                <td>{{ tanggal_indonesia($detail->invoice->tanggal ?? '') }}</td>
                                <td>{{ tanggal_indonesia($detail->invoice->jatuh_tempo ?? '') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->invoice->total ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data invoice</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('kontrabon.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('kontrabon.cetak', $kontrabon->id) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="fas fa-print"></i> Cetak PDF
                </a>
            </div>

        </div>
    </div>
@stop
