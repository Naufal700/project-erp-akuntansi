<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use App\Imports\GudangImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TemplateGudangExport;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::all();
        return view('gudang.index', compact('gudangs'));
    }

    // Tambah Gudang
    public function create()
    {
        return view('gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gudang' => 'required|unique:gudang,kode_gudang',
            'nama_gudang' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Gudang::create($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil ditambahkan.');
    }

    // Edit Gudang
    public function edit($id)
    {
        $gudang = Gudang::findOrFail($id);
        return view('gudang.edit', compact('gudang'));
    }

    public function update(Request $request, $id)
    {
        $gudang = Gudang::findOrFail($id);

        $request->validate([
            'kode_gudang' => 'required|unique:gudang,kode_gudang,' . $gudang->id,
            'nama_gudang' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $gudang->update($request->all());

        return redirect()->route('gudang.index')->with('success', 'Gudang berhasil diperbarui.');
    }
    public function exportTemplate()
    {
        return Excel::download(new TemplateGudangExport, 'template_gudang.xlsx');
    }

    public function importGudang(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new GudangImport, $request->file('file'));

        return redirect()->route('gudang.index')->with('success', 'Data gudang berhasil diimpor.');
    }
}
