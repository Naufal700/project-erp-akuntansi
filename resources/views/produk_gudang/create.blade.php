@extends('adminlte::page')

@section('title', 'Tambah Stok Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Tambah Stok Gudang</h1>
@stop

@section('content')
    <form action="{{ route('produk-gudang.store') }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="id_produk">Produk</label>
                    <select name="id_produk" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_gudang">Gudang</label>
                    <select name="id_gudang" class="form-control" required>
                        <option value="">-- Pilih Gudang --</option>
                        @foreach ($gudangs as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" step="0.01" name="stok" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="stok_minimal">Stok Minimal</label>
                    <input type="number" step="0.01" name="stok_minimal" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('produk-gudang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
@endsection
