@extends('adminlte::page')

@section('title', 'Tambah Produk')

@section('content_header')
    <h1 class="mb-3">Tambah Produk</h1>
@stop

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">

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

            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="row">

                    {{-- Kode Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Kode Produk</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" name="kode_produk" class="form-control"
                                value="{{ old('kode_produk', $autoKode ?? '') }}" readonly>
                        </div>
                    </div>

                    {{-- Nama Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Nama Produk</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                    </div>

                    {{-- Satuan --}}
                    <div class="col-md-6 mb-3">
                        <label>Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                            <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}">
                        </div>
                    </div>

                    {{-- Harga Beli --}}
                    <div class="col-md-6 mb-3">
                        <label>Harga Beli</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" step="0.01" name="harga_beli" class="form-control"
                                value="{{ old('harga_beli') }}">
                        </div>
                    </div>

                    {{-- Harga Jual --}}
                    <div class="col-md-6 mb-3">
                        <label>Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                            <input type="number" step="0.01" name="harga_jual" class="form-control"
                                value="{{ old('harga_jual') }}">
                        </div>
                    </div>
                    {{-- Saldo Awal Qty --}}
                    <div class="col-md-6 mb-3">
                        <label>Saldo Awal Qty</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            <input type="number" name="saldo_awal_qty" class="form-control"
                                value="{{ old('saldo_awal_qty', 0) }}">
                        </div>
                    </div>

                    {{-- Saldo Awal Harga --}}
                    <div class="col-md-6 mb-3">
                        <label>Saldo Awal Harga</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" step="0.01" name="saldo_awal_harga" class="form-control"
                                value="{{ old('saldo_awal_harga', 0) }}">
                        </div>
                    </div>
                    {{-- Stok --}}
                    <div class="col-md-6 mb-3">
                        <label>Stok</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}">
                        </div>
                    </div>

                    {{-- Stok Minimal --}}
                    <div class="col-md-6 mb-3">
                        <label>Stok Minimal</label>
                        <input type="number" name="stok_minimal" class="form-control"
                            value="{{ old('stok_minimal', 0) }}">
                    </div>

                    {{-- Tipe Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Tipe Produk</label>
                        <select name="tipe_produk" class="form-control">
                            @foreach (['barang', 'jasa', 'biaya', 'non_stok'] as $tipe)
                                <option value="{{ $tipe }}" @selected(old('tipe_produk') == $tipe)>
                                    {{ ucfirst($tipe) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Stok --}}
                    <div class="col-md-6 mb-3">
                        <label>Tipe Stok</label>
                        <select name="tipe_stok" class="form-control">
                            @foreach (['fifo', 'average'] as $tipe)
                                <option value="{{ $tipe }}" @selected(old('tipe_stok') == $tipe)>
                                    {{ strtoupper($tipe) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-6 mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->nama_kategori }}" @selected(old('kategori') == $kategori->nama_kategori)>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div class="col-md-6 mb-3">
                        <label>Supplier</label>
                        <select name="supplier" class="form-control">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach ($supplierList as $supplier)
                                <option value="{{ $supplier->nama }}" @selected(old('supplier') == $supplier->nama)>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Barcode --}}
                    <div class="col-md-6 mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                    </div>

                    {{-- Lokasi Rak --}}
                    <div class="col-md-6 mb-3">
                        <label>Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" class="form-control" value="{{ old('lokasi_rak') }}">
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-12 mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="3" class="form-control">{{ old('keterangan') }}</textarea>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" @selected(old('is_active', 1) == 1)>Aktif</option>
                            <option value="0" @selected(old('is_active') == 0)>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">
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
