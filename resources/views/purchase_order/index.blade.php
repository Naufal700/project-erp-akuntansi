@extends('adminlte::page')

@section('title', 'Purchase Order')

@section('content_header')
    <h1 class="font-weight-bold">Purchase Order</h1>
@stop
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor PO..."
                        value="{{ request('search') }}" aria-label="Cari nomor po">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-search">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('purchase-order.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('purchase-order.create') }}" class="btn btn-success mr-2" title="Tambah PO">
                    <i class="fas fa-plus-circle"></i> Tambah PO
                </a>
                <form method="GET" action="{{ route('penerimaan.index') }}" class="form-inline">
                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                        class="form-control mr-2" placeholder="Dari Tanggal">
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                        class="form-control mr-2" placeholder="Sampai Tanggal">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </form>
            </form>

            {{-- Tabel PO --}}
            <div class="table-responsive">
                <table class="table table-sm table-hover table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor PO</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $po)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ tanggal_indonesia($po->tanggal) }}</td>
                                <td class="text-nowrap">{{ $po->nomor_po }}</td>
                                <td>{{ $po->supplier->nama }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge 
                                        @if ($po->status == 'draft') bg-secondary 
                                        @elseif($po->status == 'diproses') bg-warning 
                                        @elseif($po->status == 'selesai') bg-success 
                                        @else bg-danger @endif">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </td>
                                <td class="text-end">Rp {{ number_format($po->total, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('purchase-order.show', $po->id) }}" class="btn btn-info btn-sm"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('purchase-order.edit', $po->id) }}" class="btn btn-warning btn-sm"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('purchase-order.destroy', $po->id) }}" method="POST"
                                        style="display:inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
