@extends('adminlte::page')

@section('title', 'Edit Supplier')

@section('content_header')
    <h1>Edit Supplier</h1>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-edit"></i> Form Edit Data Supplier</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label><i class="fas fa-user"></i> Nama Supplier <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control"
                        value="{{ old('nama', $supplier->nama) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $supplier->alamat) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label><i class="fas fa-phone"></i> Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="form-control"
                        value="{{ old('telepon', $supplier->telepon) }}">
                </div>

                <div class="form-group mb-4">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $supplier->email) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

@stop
