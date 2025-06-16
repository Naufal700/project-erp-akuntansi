<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Models\Coa;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        // Ambil data metode pembayaran dengan relasi coa supaya nama akun bisa diakses
        $metodes = MetodePembayaran::with('coa')->get();

        return view('metode_pembayaran.index', compact('metodes'));
    }

    public function create()
    {
        $akunKasBank = Coa::whereIn('tipe_akun', ['kas', 'bank'])->get();
        return view('metode_pembayaran.create', compact('akunKasBank'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required|in:kas,bank',
            'kode_akun' => 'required|exists:coa,kode_akun',
        ]);

        MetodePembayaran::create($request->all());

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $metode = MetodePembayaran::findOrFail($id);
        $akunKasBank = Coa::whereIn('jenis', ['kas', 'bank'])->get();
        return view('metode_pembayaran.edit', compact('metode', 'akunKasBank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'tipe' => 'required|in:kas,bank',
            'kode_akun' => 'required|exists:coa,kode_akun',
        ]);

        MetodePembayaran::findOrFail($id)->update($request->all());

        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        MetodePembayaran::destroy($id);
        return redirect()->route('metode-pembayaran.index')->with('success', 'Metode berhasil dihapus.');
    }
}
