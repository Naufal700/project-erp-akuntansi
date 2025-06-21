<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoaExport;
use App\Imports\CoaImport;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
                    'Hutang Jangka Pendek',
                    'Hutang Jangka Panjang',
                    'Persediaan',
                    'Aset Tetap',
                    'HPP',
                    'Penyesuaian',
                    'Lainnya'
                ]),
            ],
            'parent_kode' => 'nullable|string|exists:coa,kode_akun',
            'level' => 'nullable|integer',
            'saldo_awal_debit' => 'nullable|numeric|required_without:saldo_awal_kredit',
            'saldo_awal_kredit' => 'nullable|numeric|required_without:saldo_awal_debit',
            'periode_saldo_awal' => 'required|date_format:Y-m', // Validasi tambahan
        ]);

        $validated['saldo_awal_debit'] = $validated['saldo_awal_debit'] ?? 0;
        $validated['saldo_awal_kredit'] = $validated['saldo_awal_kredit'] ?? 0;

        Coa::create([
            'kode_akun' => $validated['kode_akun'],
            'nama_akun' => $validated['nama_akun'],
            'tipe_akun' => $validated['tipe_akun'],
            'parent_kode' => $validated['parent_kode'] ?? null,
            'level' => $validated['level'] ?? null,
            'saldo_awal_debit' => $validated['saldo_awal_debit'],
            'saldo_awal_kredit' => $validated['saldo_awal_kredit'],
            'periode_saldo_awal' => $validated['periode_saldo_awal'],
        ]);

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
                    'Hutang Jangka Pendek',
                    'Hutang Jangka Panjang',
                    'Persediaan',
                    'Aset Tetap',
                    'HPP',
                    'Penyesuaian',
                    'Lainnya'
                ]),
            ],
            'parent_kode' => 'nullable|string|exists:coa,kode_akun',
            'level' => 'nullable|integer',
            'saldo_awal_debit' => 'nullable|numeric|required_without:saldo_awal_kredit',
            'saldo_awal_kredit' => 'nullable|numeric|required_without:saldo_awal_debit',
            'periode_saldo_awal' => 'required|date_format:Y-m',
        ]);

        $coa = Coa::findOrFail($kode_akun);

        $coa->update([
            'nama_akun' => $validated['nama_akun'],
            'tipe_akun' => $validated['tipe_akun'],
            'parent_kode' => $validated['parent_kode'] ?? null,
            'level' => $validated['level'] ?? null,
            'saldo_awal_debit' => $validated['saldo_awal_debit'] ?? 0,
            'saldo_awal_kredit' => $validated['saldo_awal_kredit'] ?? 0,
            'periode_saldo_awal' => $validated['periode_saldo_awal'],
        ]);

        return redirect()->route('coa.index')->with('success', 'COA berhasil diupdate.');
    }

    public function destroy($kode_akun)
    {
        $coa = Coa::findOrFail($kode_akun);
        $coa->delete();

        return redirect()->route('coa.index')->with('success', 'COA berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new CoaExport, 'template_coa.xlsx');
    }

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
        $selected = $request->input('selected', []);

        if (empty($selected)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        $valid = collect($selected)->every(fn($kode) => is_string($kode) && trim($kode) !== '');
        if (!$valid) {
            return back()->with('error', 'Data yang dikirim tidak valid.');
        }

        $deleted = Coa::whereIn('kode_akun', $selected)->delete();

        return $deleted
            ? back()->with('success', "$deleted data COA berhasil dihapus.")
            : back()->with('error', 'Tidak ada data yang berhasil dihapus.');
    }
}
