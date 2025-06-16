@extends('adminlte::page')

@section('title', 'Kategori Produk')

@section('content_header')
    <h1>Kategori Produk</h1>
@stop

@section('content')
    <a href="{{ route('kategori-produk.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $row)
                <tr>
                    <td>{{ $row->kode_kategori }}</td>
                    <td>{{ $row->nama_kategori }}</td>
                    <td>{{ $row->deskripsi }}</td>
                    <td>
                        <span class="badge badge-{{ $row->is_active ? 'success' : 'secondary' }}">
                            {{ $row->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('kategori-produk.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('kategori-produk.destroy', $row->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $kategori->links() }}
@stop
