@extends('adminlte::page')

@section('title', 'Daftar Pengiriman')

@section('content_header')
    <h1>Daftar Pengiriman</h1>
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
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari pengiriman penjualan...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('pengiriman-penjualan.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('pengiriman-penjualan.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus-circle"></i> Pengiriman Penjualan
                </a>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Tanggal Pengiriman</th>
                            <th>Nomor Surat Jalan</th>
                            <th>Sales Order</th>
                            <th>Nama Pelanggan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengiriman as $item)
                            <tr>
                                <td>{{ tanggal_indonesia($item->tanggal) }}</td>
                                <td>{{ $item->nomor_surat_jalan }}</td>
                                <td>{{ $item->salesOrder->nomor_so ?? '-' }}</td>
                                <td>{{ $item->salesOrder->pelanggan->nama ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('pengiriman-penjualan.update-status', $item->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_pengiriman" class="form-control form-control-sm"
                                            onchange="this.form.submit()">
                                            <option value="draft"
                                                {{ $item->status_pengiriman == 'draft' ? 'selected' : '' }}>
                                                Draft
                                            </option>
                                            <option value="dikirim"
                                                {{ $item->status_pengiriman == 'dikirim' ? 'selected' : '' }}>
                                                Dikirim
                                            </option>
                                            <option value="diterima"
                                                {{ $item->status_pengiriman == 'diterima' ? 'selected' : '' }}>
                                                Diterima</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('pengiriman-penjualan.show', $item->id) }}"
                                        class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('pengiriman-penjualan.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('pengiriman-penjualan.destroy', $item->id) }}" method="POST"
                                        style="display:inline-block;" onsubmit="return confirm('Yakin ingin hapus data?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @stop
