@extends('adminlte::page')

@section('title', 'Tambah Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Tambah Gudang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('gudang.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="kode_gudang" class="form-label">Kode Gudang</label>
                    <input type="text" name="kode_gudang" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nama_gudang" class="form-label">Nama Gudang</label>
                    <input type="text" name="nama_gudang" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">alamat</label>
                    <input type="text" name="alamat" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
