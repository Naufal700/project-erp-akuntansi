<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalPenyesuaian;
use App\Models\Coa;

class JurnalPenyesuaianController extends Controller
{
    public function index()
    {
        $data = JurnalPenyesuaian::with('akun')->orderBy('tanggal', 'desc')->get();
        $header = 'Jurnal Penyesuaian'; // Tambahkan ini
        return view('jurnal_penyesuaian.index', compact('data', 'header'));
    }

    public function create()
    {
        $akun = Coa::orderBy('kode_akun')->get();
        return view('jurnal_penyesuaian.create', compact('akun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_akun' => 'required',
            'nominal_debit' => 'nullable|numeric',
            'nominal_kredit' => 'nullable|numeric',
        ]);

        JurnalPenyesuaian::create($request->all());

        return redirect()->route('jurnal-penyesuaian.index')->with('success', 'Data berhasil disimpan');
    }

    public function destroy($id)
    {
        JurnalPenyesuaian::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
