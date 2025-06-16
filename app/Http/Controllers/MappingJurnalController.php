<?php

namespace App\Http\Controllers;

use App\Models\MappingJurnal;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;


class MappingJurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = MappingJurnal::with(['akunDebit', 'akunKredit']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('modul', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('kode_akun_debit', 'like', "%{$search}%")
                    ->orWhere('kode_akun_kredit', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $mapping = $query->paginate(10);
        return view('mapping_jurnal.index', compact('mapping'));
    }

    public function create()
    {
        $coas = Coa::all();
        return view('mapping_jurnal.create', compact('coas'));
    }

    // Hanya tampilkan bagian yang berubah (agar ringkas):

    // store()
    public function store(Request $request)
    {
        $validated = $request->validate([
            'modul' => 'nullable|string|max:100',
            'event' => 'nullable|string|max:100',
            'kode_akun_debit' => 'nullable|string|exists:coa,kode_akun',
            'kode_akun_kredit' => 'nullable|string|exists:coa,kode_akun',
            'keterangan' => 'nullable|string',
            'arus_kas_kelompok' => 'nullable|in:operasi,investasi,pendanaan',
            'arus_kas_jenis' => 'nullable|in:masuk,keluar',
            'arus_kas_keterangan' => 'nullable|string|max:255',
        ]);

        MappingJurnal::create($validated);

        return redirect()->route('mapping_jurnal.index')->with('success', 'Mapping jurnal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapping = MappingJurnal::findOrFail($id);
        $coas = Coa::all();

        return view('mapping_jurnal.edit', compact('mapping', 'coas'));
    }

    // update()
    public function update(Request $request, $id)
    {
        $mapping = MappingJurnal::findOrFail($id);

        $validated = $request->validate([
            'modul' => 'nullable|string|max:100',
            'event' => 'nullable|string|max:100',
            'kode_akun_debit' => 'nullable|string|exists:coa,kode_akun',
            'kode_akun_kredit' => 'nullable|string|exists:coa,kode_akun',
            'keterangan' => 'nullable|string',
            'arus_kas_kelompok' => 'nullable|in:operasi,investasi,pendanaan',
            'arus_kas_jenis' => 'nullable|in:masuk,keluar',
            'arus_kas_keterangan' => 'nullable|string|max:255',
        ]);

        $mapping->update($validated);

        return redirect()->route('mapping_jurnal.index')->with('success', 'Mapping jurnal berhasil diupdate.');
    }

    // downloadTemplate()
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header baru
        $sheet->fromArray([
            'modul',
            'event',
            'kode_akun_debit',
            'kode_akun_kredit',
            'keterangan',
            'arus_kas_kelompok',
            'arus_kas_jenis',
            'arus_kas_keterangan'
        ], null, 'A1');

        // Contoh baris
        $sheet->fromArray([
            'Penjualan',
            'Faktur Penjualan',
            '1-1101',
            '4-4101',
            'Penjualan Tunai',
            'operasi',
            'masuk',
            'Penjualan barang tunai'
        ], null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_mapping_jurnal.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    // import()
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->getRealPath();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                if ($key === 0 || !array_filter($row)) continue;

                DB::table('mapping_jurnal')->insert([
                    'modul' => $row[0] ?? null,
                    'event' => $row[1] ?? null,
                    'kode_akun_debit' => $row[2] ?? null,
                    'kode_akun_kredit' => $row[3] ?? null,
                    'keterangan' => $row[4] ?? null,
                    'arus_kas_kelompok' => $row[5] ?? null,
                    'arus_kas_jenis' => $row[6] ?? null,
                    'arus_kas_keterangan' => $row[7] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            DB::commit();
            return back()->with('success', 'Import mapping jurnal berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $mapping = MappingJurnal::findOrFail($id);
        $mapping->delete();

        return redirect()->route('mapping_jurnal.index')->with('success', 'Mapping jurnal berhasil dihapus.');
    }
}
