<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\SupplierImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierTemplateExport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierController extends Controller
{
    // Tampilkan daftar dengan search dan pagination
    public function index(Request $request)
    {
        $search = $request->query('search');
        $suppliers = Supplier::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%$search%")
                ->orWhere('alamat', 'like', "%$search%")
                ->orWhere('telepon', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })->orderBy('id', 'desc');
        $suppliers = $suppliers->paginate(10);

        return view('supplier.index', compact('suppliers', 'search'));
    }

    // Form tambah
    public function create()
    {
        return view('supplier.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    // Form edit
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    // Update data
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
        ]);

        $supplier->update($request->all());

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diupdate');
    }

    // Hapus data
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus');
    }

    // Download template Excel
    public function downloadTemplate()
    {
        return Excel::download(new SupplierTemplateExport, 'template_supplier.xlsx');
    }

    // Import data dari Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SupplierImport, $request->file('file'));
            return redirect()->route('supplier.index')->with('success', 'Data berhasil diimport');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
