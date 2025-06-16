@extends('adminlte::page')

@section('title', 'Edit Gudang')

@section('content_header')
    <h1 class="font-weight-bold">Edit Gudang</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('gudang.update', $gudang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="kode_gudang" class="form-label">Kode Gudang</label>
                    <input type="text" name="kode_gudang" value="{{ $gudang->kode_gudang }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nama_gudang" class="form-label">Nama Gudang</label>
                    <input type="text" name="nama_gudang" value="{{ $gudang->nama_gudang }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">alamat</label>
                    <input type="text" name="alamat" value="{{ $gudang->alamat }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $gudang->keterangan }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('gudang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
