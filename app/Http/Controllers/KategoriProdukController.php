<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;

class KategoriProdukController extends Controller
{
    public function index()
    {
        $kategori = KategoriProduk::orderBy('created_at', 'desc')->paginate(10);
        return view('kategori_produk.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori_produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori' => 'required|unique:kategori_produk,kode_kategori',
            'nama_kategori' => 'required|string|max:100',
        ]);

        KategoriProduk::create($request->all());

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = KategoriProduk::findOrFail($id);
        return view('kategori_produk.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kategori' => 'required|unique:kategori_produk,kode_kategori,' . $id,
            'nama_kategori' => 'required|string|max:100',
        ]);

        $kategori = KategoriProduk::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriProduk::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
