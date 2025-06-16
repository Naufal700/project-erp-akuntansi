@extends('adminlte::page')

@section('title', 'Daftar Retur Pembelian')

@section('content_header')
    <h1 class="font-weight-bold">Daftar Retur Pembelian</h1>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-right">
        <a href="{{ route('retur-pembelian.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Retur
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Retur</th>
                        <th>Tanggal</th>
                        <th>Nomor Penerimaan</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($retur as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nomor_retur }}</td>
                            <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                            <td>{{ $item->penerimaan->nomor_penerimaan ?? '-' }}</td>
                            <td class="text-right">{{ number_format($item->total, 2, ',', '.') }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                <a href="{{ route('retur-pembelian.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('retur-pembelian.print', $item->id) }}" class="btn btn-secondary btn-sm"
                                    target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('retur-pembelian.destroy', $item->id) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Yakin ingin menghapus retur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data retur pembelian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop
