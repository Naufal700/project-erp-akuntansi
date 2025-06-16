@extends('adminlte::page')

@section('title', 'Daftar Gudang')
@section('content_header')
    <h1 class="font-weight-bold">Daftar Gudang</h1>
@stop
@section('content')
    <a href="{{ route('gudang.create') }}" class="btn btn-primary mb-3">+ Tambah Gudang</a>
    <div class="mb-3">
        <a href="{{ route('gudang.template.export') }}" class="btn btn-outline-success btn-sm">📤 Download Template</a>

        <form action="{{ route('gudang.import') }}" method="POST" enctype="multipart/form-data" class="d-inline-block">
            @csrf
            <input type="file" name="file" required class="form-control-file d-inline-block" style="width: 200px;">
            <button type="submit" class="btn btn-outline-primary btn-sm">📥 Import Excel</button>
        </form>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gudangs as $g)
                <tr>
                    <td>{{ $g->kode_gudang }}</td>
                    <td>{{ $g->nama_gudang }}</td>
                    <td>{{ $g->alamat }}</td>
                    <td>{{ $g->keterangan }}</td>
                    <td>
                        <a href="{{ route('gudang.edit', $g->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('gudang.destroy', $g->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
