<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $query = JurnalUmum::with('coa')->orderBy('tanggal', 'asc'); // ubah desc jadi asc

        // Filter tanggal
        if ($request->filled('tgl_dari')) {
            $query->where('tanggal', '>=', $request->tgl_dari);
        }
        if ($request->filled('tgl_sampai')) {
            $query->where('tanggal', '<=', $request->tgl_sampai);
        }

        // Search kode akun, keterangan, ref
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_akun', 'like', "%$search%")
                    ->orWhere('keterangan', 'like', "%$search%")
                    ->orWhere('ref', 'like', "%$search%");
            });
        }

        $jurnals = $query->paginate(10);

        return view('jurnal_umum.index', compact('jurnals'));
    }
    public function create()
    {
        $coa = Coa::orderBy('kode_akun')->get();
        return view('jurnal_umum.create', compact('coa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'details' => 'required|array|min:2', // minimal 2 baris
            'details.*.kode_akun' => 'required|string|exists:coa,kode_akun',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.kredit' => 'required|numeric|min:0',
            'details.*.keterangan' => 'nullable|string',
            'ref' => 'nullable|string',
            'modul' => 'nullable|string',
        ]);

        $totalDebit = collect($request->details)->sum(function ($item) {
            return floatval($item['debit']);
        });
        $totalKredit = collect($request->details)->sum(function ($item) {
            return floatval($item['kredit']);
        });

        if (round($totalDebit, 2) <= 0 || round($totalKredit, 2) <= 0) {
            return back()->withInput()->withErrors(['details' => 'Total debit dan kredit harus lebih dari 0.']);
        }

        if (round($totalDebit, 2) !== round($totalKredit, 2)) {
            return back()->withInput()->withErrors(['details' => 'Total debit dan kredit harus sama (balance).']);
        }

        // Simpan tiap baris jurnal
        foreach ($request->details as $detail) {
            // Abaikan baris yang debit dan kreditnya 0 semua (jika ada)
            if (floatval($detail['debit']) == 0 && floatval($detail['kredit']) == 0) {
                continue;
            }

            JurnalUmum::create([
                'tanggal' => $request->tanggal,
                'kode_akun' => $detail['kode_akun'],
                'nominal_debit' => $detail['debit'],
                'nominal_kredit' => $detail['kredit'],
                'keterangan' => $detail['keterangan'] ?? null,
                'ref' => $request->ref,
                'modul' => $request->modul,
            ]);
        }

        return redirect()->route('jurnal_umum.index')->with('success', 'Jurnal berhasil disimpan.');
    }


    public function edit($id)
    {
        $jurnal = \App\Models\JurnalUmum::with('details')->findOrFail($id);
        $coas = \App\Models\Coa::orderBy('kode_akun')->get();

        return view('jurnal_umum.edit', compact('jurnal', 'coas'));
    }


    // Update jurnal ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_akun' => 'required|string',
            'nominal_debit' => 'required|numeric|min:0',
            'nominal_kredit' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'ref' => 'nullable|string',
            'modul' => 'nullable|string',
        ]);

        // Validasi saldo harus balance
        if ($request->nominal_debit != $request->nominal_kredit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nominal_kredit' => 'Nominal debit dan kredit harus sama (balance).']);
        }

        $jurnal = JurnalUmum::findOrFail($id);
        $jurnal->tanggal = $request->tanggal;
        $jurnal->kode_akun = $request->kode_akun;
        $jurnal->nominal_debit = $request->nominal_debit;
        $jurnal->nominal_kredit = $request->nominal_kredit;
        $jurnal->keterangan = $request->keterangan;
        $jurnal->ref = $request->ref;
        $jurnal->modul = $request->modul;
        $jurnal->save();

        return redirect()->route('jurnal_umum.index')
            ->with('success', 'Data jurnal berhasil diperbarui.');
    }

    // Hapus jurnal
    public function destroy($id)
    {
        $jurnal = JurnalUmum::findOrFail($id);
        $jurnal->delete();

        return redirect()->route('jurnal_umum.index')
            ->with('success', 'Data jurnal berhasil dihapus.');
    }
    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids) || count($ids) == 0) {
            return redirect()->back()->withErrors('Tidak ada jurnal yang dipilih untuk dihapus.');
        }

        // Hapus semua jurnal dengan id yang dipilih
        JurnalUmum::whereIn('id', $ids)->delete();

        return redirect()->route('jurnal_umum.index')->with('success', 'Jurnal yang dipilih berhasil dihapus.');
    }
}
