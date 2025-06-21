@extends('adminlte::page')

@section('title', 'Mapping Jurnal')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-4">Mapping Jurnal</h1>
    </div>
@stop

@section('content')
    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Pesan Error Validasi --}}
    @if ($errors->has('file'))
        <div class="alert alert-danger">
            {{ $errors->first('file') }}
        </div>
    @endif

    {{-- Pesan pencarian --}}
    @if (request('search'))
        <div class="alert alert-info">
            Hasil pencarian untuk: <strong>{{ request('search') }}</strong>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Form Pencarian --}}
            <form method="GET" class="form-inline mb-3">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari mapping jurnal...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <a href="{{ route('mapping_jurnal.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                <a href="{{ route('mapping_jurnal.create') }}" class="btn btn-success mr-2">
                    <i class="fas fa-plus-circle"></i> Tambah Mapping Jurnal
                </a>
                <a href="{{ route('mapping_jurnal.downloadTemplate') }}" class="btn btn-info mr-2">
                    <i class="fas fa-download"></i> Download Template
                </a>
            </form>

            {{-- Form Import Excel --}}
            <form action="{{ route('mapping_jurnal.import') }}" method="POST" enctype="multipart/form-data"
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

            {{-- Tabel Data --}}
            <table class="table table-hover table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Modul</th>
                        <th>Event</th>
                        <th>Akun Debit</th>
                        <th>Akun Kredit</th>
                        <th>Keterangan</th>
                        <th>Arus Kas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapping as $m)
                        <tr>
                            <td>{{ $m->modul }}</td>
                            <td>{{ $m->event }}</td>
                            <td>{{ $m->kode_akun_debit }} - {{ $m->akunDebit->nama_akun ?? '-' }}</td>
                            <td>{{ $m->kode_akun_kredit }} - {{ $m->akunKredit->nama_akun ?? '-' }}</td>
                            <td>{{ $m->keterangan }}</td>
                            <td>
                                @if ($m->arus_kas_kelompok)
                                    <strong>{{ ucfirst($m->arus_kas_kelompok) }}</strong><br>
                                    Jenis: {{ ucfirst($m->arus_kas_jenis) }}<br>
                                    Ket: {{ $m->arus_kas_keterangan }}
                                @else
                                    <em>-</em>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('mapping_jurnal.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mapping_jurnal.destroy', $m->id) }}" method="POST"
                                    style="display:inline-block;" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $mapping->links() }}
            </div>

        </div>
    </div>
@stop
