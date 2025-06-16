@extends('adminlte::page')

@section('title', 'Master Supplier')

@section('content_header')
    <h1 class="mb-4">Master Supplier</h1>
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
            {{-- Search & Buttons --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Search Form --}}
                    <form method="GET" class="form-inline mb-3">
                        <div class="input-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari supplier..."
                                value="{{ request('search') }}" aria-label="Cari nama supplier">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" id="button-search">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                        {{-- Action Buttons --}}
                        <a href="{{ route('supplier.create') }}" class="btn btn-success mr-2" title="Tambah Supplier">
                            <i class="fas fa-plus-circle"></i> Tambah Supplier
                        </a>
                        <a href="{{ route('supplier.downloadTemplate') }}" class="btn btn-info mr-2"
                            title="Download Template Excel">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </form>


                    {{-- Import Form --}}
                    <form action="{{ route('supplier.import') }}" method="POST" enctype="multipart/form-data"
                        class="form-inline mb-3">
                        @csrf
                        <div class="form-group mr-2">
                            <input type="file" name="file" accept=".xls,.xlsx" required class="form-control"
                                aria-label="Upload file Excel">
                        </div>
                        <button class="btn btn-warning d-flex align-items-center gap-1" type="submit"
                            style="white-space: nowrap;">
                            <i class="fas fa-file-import"></i> Import Excel
                        </button>
                    </form>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th class="text-center" style="width: 130px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->nama }}</td>
                                        <td>{{ $supplier->alamat }}</td>
                                        <td>{{ $supplier->telepon }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                class="btn btn-sm btn-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->nama }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center fst-italic text-muted py-4">Data tidak
                                            ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $suppliers->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            </div>
        @stop
