@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <h1 class="mb-4">Edit Customer</h1>
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
            <h5 class="mb-0"><i class="fas fa-user-edit"></i> Form Edit Data Customer</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label><i class="fas fa-user"></i> Nama Customer<span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control"
                        value="{{ old('nama', $customer->nama) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $customer->alamat) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label><i class="fas fa-phone"></i> Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="form-control"
                        value="{{ old('telepon', $customer->telepon) }}">
                </div>

                <div class="form-group mb-4">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $customer->email) }}">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customer.index') }}" class="btn btn-secondary">
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
