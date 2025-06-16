@extends('adminlte::page')

@section('title', 'Tambah Customer')

@section('content_header')
    <h1 class="mb-3">Tambah Customer</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Ada kesalahan input:<br>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card shadow rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> Form Input Data Customer</h5>
                </div>
                <div class="card-body">
                    {{-- Form Input --}}
                    <form action="{{ route('customer.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nama Customer <span class="text-danger">*</span></label>
                            <input name="nama" class="form-control" placeholder="Masukan nama customer" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Telepon</label>
                            <input name="telepon" class="form-control" placeholder="Masukkan nomor telepon">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Masukkan alamat email">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @stop
