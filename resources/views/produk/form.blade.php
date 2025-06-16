{{-- resources/views/produk/_form.blade.php --}}
@csrf
<div class="mb-2">
    <label>Kode Produk</label>
    <input type="text" name="kode_produk" class="form-control"
        value="{{ old('kode_produk', $produk->kode_produk ?? '') }}">
</div>
<div class="mb-2">
    <label>Nama</label>
    <input type="text" name="nama" class="form-control" value="{{ old('nama', $produk->nama ?? '') }}">
</div>
<div class="mb-2">
    <label>Satuan</label>
    <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $produk->satuan ?? '') }}">
</div>
<div class="mb-2">
    <label>Harga Beli</label>
    <input type="number" step="0.01" name="harga_beli" class="form-control"
        value="{{ old('harga_beli', $produk->harga_beli ?? '') }}">
</div>
<div class="mb-2">
    <label>Harga Jual</label>
    <input type="number" step="0.01" name="harga_jual" class="form-control"
        value="{{ old('harga_jual', $produk->harga_jual ?? '') }}">
</div>
<div class="mb-2">
    <label>Stok</label>
    <input type="number" name="stok" class="form-control" value="{{ old('stok', $produk->stok ?? 0) }}">
</div>
<div class="mb-2">
    <label>Tipe Produk</label>
    <select name="tipe_produk" class="form-control">
        @foreach (['barang', 'jasa', 'biaya', 'non_stok'] as $tipe)
            <option value="{{ $tipe }}" @if (old('tipe_produk', $produk->tipe_produk ?? '') == $tipe) selected @endif>{{ ucfirst($tipe) }}
            </option>
        @endforeach
    </select>
</div>
<button class="btn btn-primary">Simpan</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
