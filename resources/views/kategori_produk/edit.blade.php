@extends('adminlte::page')

@section('title', 'Edit Kategori Produk')

@section('content_header')
    <h1>Edit Kategori Produk</h1>
@stop

@section('content')
    <form action="{{ route('kategori-produk.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="kode_kategori">Kode Kategori</label>
            <input type="text" name="kode_kategori" class="form-control"
                value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required>
            @error('kode_kategori')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
            @error('nama_kategori')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
        </div>

        <div class="form-group">
            <label for="is_active">Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ old('is_active', $kategori->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $kategori->is_active) == '0' ? 'selected' : '' }}>Nonaktif
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('kategori-produk.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@stop
