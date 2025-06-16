@extends('adminlte::page')

@section('title', 'Import Stok Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Import Stok Gudang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('produk-gudang.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>File Excel</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <button class="btn btn-success">Import</button>
                <a href="{{ route('produk-gudang.export-template') }}" class="btn btn-info">Download Template</a>
                <a href="{{ route('produk-gudang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
