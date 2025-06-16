@extends('adminlte::page')

@section('title', 'Edit Produk')

@section('content_header')
    <h1 class="mb-4">Edit Produk</h1>
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
            <h5 class="mb-0"><i class="fas fa-user-edit"></i> Form Edit Data Produk</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Kode Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Kode Produk</label>
                        <input type="text" name="kode_produk" class="form-control"
                            value="{{ old('kode_produk', $produk->kode_produk) }}" readonly>
                    </div>

                    {{-- Nama Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama) }}">
                    </div>

                    {{-- Satuan --}}
                    <div class="col-md-6 mb-3">
                        <label>Satuan</label>
                        <input type="text" name="satuan" class="form-control"
                            value="{{ old('satuan', $produk->satuan) }}">
                    </div>

                    {{-- Harga Beli --}}
                    <div class="col-md-6 mb-3">
                        <label>Harga Beli</label>
                        <input type="number" step="0.01" name="harga_beli" class="form-control"
                            value="{{ old('harga_beli', $produk->harga_beli) }}">
                    </div>

                    {{-- Harga Jual --}}
                    <div class="col-md-6 mb-3">
                        <label>Harga Jual</label>
                        <input type="number" step="0.01" name="harga_jual" class="form-control"
                            value="{{ old('harga_jual', $produk->harga_jual) }}">
                    </div>
                    {{-- Saldo Awal Qty --}}
                    <div class="col-md-6 mb-3">
                        <label>Saldo Awal Qty</label>
                        <input type="number" name="saldo_awal_qty" class="form-control"
                            value="{{ old('saldo_awal_qty', $produk->saldo_awal_qty) }}">
                    </div>

                    {{-- Saldo Awal Harga --}}
                    <div class="col-md-6 mb-3">
                        <label>Saldo Awal Harga</label>
                        <input type="number" step="0.01" name="saldo_awal_harga" class="form-control"
                            value="{{ old('saldo_awal_harga', $produk->saldo_awal_harga) }}">
                    </div>

                    {{-- Stok --}}
                    <div class="col-md-6 mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok) }}">
                    </div>

                    {{-- Stok Minimal --}}
                    <div class="col-md-6 mb-3">
                        <label>Stok Minimal</label>
                        <input type="number" name="stok_minimal" class="form-control"
                            value="{{ old('stok_minimal', $produk->stok_minimal) }}">
                    </div>

                    {{-- Tipe Produk --}}
                    <div class="col-md-6 mb-3">
                        <label>Tipe Produk</label>
                        <select name="tipe_produk" class="form-control">
                            @foreach (['barang', 'jasa', 'biaya', 'non_stok'] as $tipe)
                                <option value="{{ $tipe }}" @selected(old('tipe_produk', $produk->tipe_produk) == $tipe)>
                                    {{ ucfirst($tipe) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Stok --}}
                    <div class="col-md-6 mb-3">
                        <label>Tipe Stok</label>
                        <select name="tipe_stok" class="form-control">
                            @foreach (['fifo', 'average', 'stok', 'non_stok'] as $tipe)
                                <option value="{{ $tipe }}" @selected(old('tipe_stok', $produk->tipe_stok) == $tipe)>
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
                                <option value="{{ $kategori->nama_kategori }}" @selected(old('kategori', $produk->kategori) == $kategori->nama_kategori)>
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
                                <option value="{{ $supplier->nama }}" @selected(old('supplier', $produk->supplier) == $supplier->nama)>
                                    {{ $supplier->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Barcode --}}
                    <div class="col-md-6 mb-3">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control"
                            value="{{ old('barcode', $produk->barcode) }}">
                    </div>

                    {{-- Lokasi Rak --}}
                    <div class="col-md-6 mb-3">
                        <label>Lokasi Rak</label>
                        <input type="text" name="lokasi_rak" class="form-control"
                            value="{{ old('lokasi_rak', $produk->lokasi_rak) }}">
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-md-12 mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="3" class="form-control">{{ old('keterangan', $produk->keterangan) }}</textarea>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="col-md-6 mb-3">
                        <label>Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" @selected(old('is_active', $produk->is_active) == 1)>Aktif</option>
                            <option value="0" @selected(old('is_active', $produk->is_active) == 0)>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">
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
