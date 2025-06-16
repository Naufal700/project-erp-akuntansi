@extends('adminlte::page')

@section('title', 'Edit Stok Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Edit Stok Gudang</h1>
@stop

@section('content')
    <form action="{{ route('produk-gudang.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Produk</label>
                    <input type="text" class="form-control" value="{{ $item->produk->nama }}" disabled>
                </div>

                <div class="form-group">
                    <label>Gudang</label>
                    <input type="text" class="form-control" value="{{ $item->gudang->nama_gudang }}" disabled>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" step="0.01" name="stok" class="form-control" value="{{ $item->stok }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="stok_minimal">Stok Minimal</label>
                    <input type="number" step="0.01" name="stok_minimal" class="form-control"
                        value="{{ $item->stok_minimal }}">
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('produk-gudang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
@endsection
