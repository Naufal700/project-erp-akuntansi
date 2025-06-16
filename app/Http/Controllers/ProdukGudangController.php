<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\ProdukGudang;
use App\Imports\ProdukGudangImport;
use App\Exports\ProdukGudangTemplateExport;
use Maatwebsite\Excel\Facades\Excel;


class ProdukGudangController extends Controller
{
    public function index()
    {
        $data = ProdukGudang::with(['produk', 'gudang'])->paginate(10);
        return view('produk_gudang.index', compact('data'));
    }

    public function create()
    {
        $produks = Produk::all();
        $gudangs = Gudang::all();
        return view('produk_gudang.create', compact('produks', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'id_gudang' => 'required|exists:gudang,id',
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'nullable|numeric|min:0',
        ]);

        ProdukGudang::updateOrCreate(
            [
                'id_produk' => $request->id_produk,
                'id_gudang' => $request->id_gudang
            ],
            [
                'stok' => $request->stok,
                'stok_minimal' => $request->stok_minimal ?? 0
            ]
        );

        return redirect()->route('produk-gudang.index')->with('success', 'Stok gudang berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = ProdukGudang::findOrFail($id);
        $produks = Produk::all();
        $gudangs = Gudang::all();
        return view('produk_gudang.edit', compact('item', 'produks', 'gudangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|numeric|min:0',
            'stok_minimal' => 'nullable|numeric|min:0',
        ]);

        $item = ProdukGudang::findOrFail($id);
        $item->update([
            'stok' => $request->stok,
            'stok_minimal' => $request->stok_minimal ?? 0
        ]);

        return redirect()->route('produk-gudang.index')->with('success', 'Stok gudang berhasil diupdate.');
    }

    public function destroy($id)
    {
        ProdukGudang::destroy($id);
        return redirect()->route('produk-gudang.index')->with('success', 'Data stok gudang dihapus.');
    }
    public function importForm()
    {
        return view('produk_gudang.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ProdukGudangImport, $request->file('file'));

        return redirect()->route('produk-gudang.index')->with('success', 'Import data stok gudang berhasil.');
    }

    public function exportTemplate()
    {
        return Excel::download(new ProdukGudangTemplateExport, 'template_produk_gudang.xlsx');
    }
}
