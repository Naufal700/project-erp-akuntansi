<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoaExport;
use App\Imports\CoaImport;
use Illuminate\Validation\Rule;


class CoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Coa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('kode_akun', 'like', "%$search%")
                ->orWhere('nama_akun', 'like', "%$search%");
        }

        $coas = $query->orderBy('kode_akun')->paginate(15);

        return view('coa.index', compact('coas'));
    }

    public function create()
    {
        $parents = Coa::pluck('nama_akun', 'kode_akun');
        return view('coa.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|string|max:20|unique:coa,kode_akun',
            'nama_akun' => 'required|string|max:100',
            'tipe_akun' => [
                'required',
                Rule::in([
                    'Aset',
                    'Kewajiban',
                    'Modal',
                    'Pendapatan',
                    'Beban',
                    'Kas',
                    'Bank',
                    'Piutang',
                    'Hutang',
                    'Persediaan',
                    'Aset Tetap',
                    'HPP',
                    'Penyesuaian',
                    'Lainnya',
                ]),
            ],
            'parent_kode' => 'nullable|string|exists:coa,kode_akun',
            'level' => 'nullable|integer',
            'saldo_awal' => 'required|numeric',
        ]);
        Coa::create($validated);

        return redirect()->route('coa.index')->with('success', 'COA berhasil ditambahkan.');
    }

    public function edit($kode_akun)
    {
        $coa = Coa::findOrFail($kode_akun);
        $parents = Coa::where('kode_akun', '!=', $kode_akun)->pluck('nama_akun', 'kode_akun');
        return view('coa.edit', compact('coa', 'parents'));
    }

    public function update(Request $request, $kode_akun)
    {
        // Validasi dulu
        $validated = $request->validate([
            'nama_akun' => 'required|string|max:100',
            'tipe_akun' => [
                'required',
                Rule::in([
                    'Aset',
                    'Kewajiban',
                    'Modal',
                    'Pendapatan',
                    'Beban',
                    'Kas',
                    'Bank',
                    'Piutang',
                    'Hutang',
                    'Persediaan',
                    'Aset Tetap',
                    'HPP',
                    'Penyesuaian',
                    'Lainnya',
                ]),
            ],
            'parent_kode' => 'nullable|string|exists:coa,kode_akun',
            'level' => 'nullable|integer',
            'saldo_awal' => 'required|numeric',
        ]);

        // Ambil data yang mau diupdate
        $coa = Coa::where('kode_akun', $kode_akun)->firstOrFail();

        // Update data dengan hasil validasi
        $coa->update($validated);

        // Debug (optional)
        info('Validated tipe_akun: ' . $validated['tipe_akun'] . ' (' . gettype($validated['tipe_akun']) . ')');
        info('Kode akun param: ' . $kode_akun . ' (' . gettype($kode_akun) . ')');

        return redirect()->route('coa.index')->with('success', 'COA berhasil diupdate.');
    }

    public function destroy($kode_akun)
    {
        $coa = Coa::findOrFail($kode_akun);
        $coa->delete();

        return redirect()->route('coa.index')->with('success', 'COA berhasil dihapus.');
    }

    // Export template
    public function downloadTemplate()
    {
        return Excel::download(new CoaExport, 'template_coa.xlsx');
    }

    // Import COA
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new CoaImport, $request->file('file'));
            return redirect()->route('coa.index')->with('success', 'Import COA berhasil.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
    public function bulkDelete(Request $request)
    {
        if ($request->has('selected')) {
            Coa::whereIn('kode_akun', $request->selected)->delete();
            return back()->with('success', 'Beberapa data COA berhasil dihapus.');
        }

        return back()->with('error', 'Tidak ada data yang dipilih.');
    }
}
