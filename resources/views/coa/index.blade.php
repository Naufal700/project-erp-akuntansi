@extends('adminlte::page')

@section('title', 'Master Data COA')

@section('content_header')
    <h1 class="mb-3">Master Data COA</h1>
@stop

@section('content')
    <div class="container-fluid">

        {{-- Alert --}}
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

                {{-- Toolbar & Import --}}
                <form method="GET" class="form-inline mb-3">
                    <div class="input-group mr-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari nama atau kode akun">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('coa.index') }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                    <a href="{{ route('coa.create') }}" class="btn btn-success mr-2">
                        <i class="fas fa-plus"></i> Tambah COA
                    </a>
                    <a href="{{ route('coa.downloadTemplate') }}" class="btn btn-info mr-2">
                        <i class="fas fa-file-download"></i> Download Template
                    </a>
                    {{-- Tombol Hapus Terpilih --}}
                    <button type="submit" class="btn btn-danger" {{ $coas->count() ? '' : 'disabled' }}>
                        <i class="fas fa-trash"></i> Hapus Terpilih
                    </button>
                </form>

                {{-- Import Form --}}
                <form action="{{ route('coa.import') }}" method="POST" enctype="multipart/form-data"
                    class="form-inline mb-3">
                    @csrf
                    <input type="file" name="file" accept=".xls,.xlsx" required class="form-control mr-2">
                    <button class="btn btn-warning" type="submit">
                        <i class="fas fa-file-import"></i> Import Excel
                    </button>
                </form>

                {{-- Bulk Delete Form --}}
                <form action="{{ route('coa.bulkDelete') }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data terpilih?')">
                    @csrf
                    @method('DELETE')

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="30"><input type="checkbox" id="select-all"></th>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Tipe Akun</th>
                                    <th>Parent Kode</th>
                                    <th>Level</th>
                                    <th class="text-end">Saldo Awal</th>
                                    <th width="120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coas as $coa)
                                    <tr>
                                        <td><input type="checkbox" name="selected[]" value="{{ $coa->kode_akun }}"></td>
                                        <td>{{ $coa->kode_akun }}</td>
                                        <td>{{ $coa->nama_akun }}</td>
                                        <td>{{ $coa->tipe_akun }}</td>
                                        <td>{{ $coa->parent_kode ?? '-' }}</td>
                                        <td class="text-center">{{ $coa->level }}</td>
                                        <td class="text-end">{{ number_format($coa->saldo_awal, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('coa.edit', $coa->kode_akun) }}"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('coa.destroy', $coa->kode_akun) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
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
                                        <td colspan="8" class="text-center text-muted">Tidak ada data COA ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $coas->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Select All Checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@stop
