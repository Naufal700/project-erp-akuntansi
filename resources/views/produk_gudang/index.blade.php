@extends('adminlte::page')

@section('title', 'Stok Produk per Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Stok Produk per Gudang</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('produk-gudang.create') }}" class="btn btn-primary mr-2">+ Tambah Stok Gudang</a>
        <a href="{{ route('produk-gudang.export-template') }}" class="btn btn-success mr-2">📥 Download Template</a>
        <a href="{{ route('produk-gudang.import.form') }}" class="btn btn-secondary">📤 Import Excel</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Gudang</th>
                <th>Stok</th>
                <th>Stok Minimal</th>
                <th>Terakhir Diperbarui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->gudang->nama_gudang }}</td>
                    <td>{{ number_format($item->stok, 2) }}</td>
                    <td>{{ number_format($item->stok_minimal, 2) }}</td>
                    <td>{{ $item->last_updated }}</td>
                    <td>
                        <a href="{{ route('produk-gudang.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('produk-gudang.destroy', $item->id) }}" method="POST"
                            style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $data->links() }}
@endsection
