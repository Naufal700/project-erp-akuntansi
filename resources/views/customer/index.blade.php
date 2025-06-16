@extends('adminlte::page')

@section('title', 'Data Customer')

@section('content_header')
    <h1 class="mb-3">Data Customer</h1>
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
            {{-- Form Search --}}
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama customer...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('customer.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('customer.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus-circle"></i> Tambah Customer
                </a>
                <a href="{{ route('customer.downloadTemplate') }}" class="btn btn-info mr-2">
                    <i class="fas fa-download"></i> Download Template
                </a>
            </form>

            {{-- Form Import --}}
            <form action="{{ route('customer.import') }}" method="POST" enctype="multipart/form-data"
                class="form-inline mb-3">
                @csrf
                <div class="form-group mr-2">
                    <input type="file" name="file" accept=".xls,.xlsx" required class="form-control"
                        aria-label="Upload file Excel">
                </div>
                <button class="btn btn-warning">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
            </form>

            {{-- Tabel Data Customer --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $cust)
                            <tr>
                                <td>{{ $cust->nama }}</td>
                                <td>{{ $cust->alamat }}</td>
                                <td>{{ $cust->telepon }}</td>
                                <td>{{ $cust->email }}</td>
                                <td>
                                    <a href="{{ route('customer.edit', $cust->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('customer.destroy', $cust->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end">
                {{ $customers->withQueryString()->links() }}
            </div>
        </div>
    </div>
@stop
