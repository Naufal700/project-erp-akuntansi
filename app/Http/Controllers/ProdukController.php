<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\ProdukImport;
use App\Models\KategoriProduk;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiPersediaan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukTemplateExport;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori', 'supplier');

        // Filter nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter bulan dibuat
        if ($request->filled('filter_bulan')) {
            $bulan = $request->filter_bulan;
            $query->whereMonth('created_at', '=', date('m', strtotime($bulan)))
                ->whereYear('created_at', '=', date('Y', strtotime($bulan)));
        }

        $produk = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        // Ambil produk terakhir berdasarkan kode_produk PRDxxxx
        $lastProduk = Produk::where('kode_produk', 'LIKE', 'PRD%')
            ->orderByDesc('kode_produk')
            ->first();

        // Ambil angka dari kode terakhir (PRD0025 -> 25)
        $lastKode = $lastProduk ? intval(substr($lastProduk->kode_produk, 3)) : 0;

        // Generate kode baru (contoh: PRD0026)
        $autoKode = 'PRD' . str_pad($lastKode + 1, 4, '0', STR_PAD_LEFT);
        // Ambil data kategori dan supplier
        $kategoriList = KategoriProduk::all();
        $supplierList = Supplier::all();
        return view('produk.create', compact('autoKode', 'kategoriList', 'supplierList'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:produk',
            'nama' => 'required',
            'tipe_produk' => 'required|in:barang,jasa,biaya,non_stok',
            'id_kategori' => 'nullable|exists:kategori_produk,id',
            'id_supplier' => 'nullable|exists:supplier,id',
            'stok_minimal' => 'nullable|numeric',
            'harga_beli' => 'nullable|numeric',
            'harga_jual' => 'nullable|numeric',
            'saldo_awal_qty' => 'nullable|numeric',
            'saldo_awal_harga' => 'nullable|numeric',
            'barcode' => 'nullable|string|max:100',
            'lokasi_rak' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($data['tipe_produk'] != 'barang') {
            $data['saldo_awal_qty'] = 0;
            $data['saldo_awal_harga'] = 0;
            $data['stok'] = 0;
        } else {
            $data['stok'] = $data['saldo_awal_qty'] ?? 0;
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = KategoriProduk::all();
        $supplier = Supplier::all();
        $kategoriList = KategoriProduk::all();
        $supplierList = Supplier::all();
        return view('produk.edit', compact('produk', 'kategoriList', 'supplierList'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'kode_produk' => 'required|unique:produk,kode_produk,' . $id,
            'nama' => 'required',
            'tipe_produk' => 'required|in:barang,jasa,biaya,non_stok',
            'id_kategori' => 'nullable|exists:kategori_produk,id',
            'id_supplier' => 'nullable|exists:supplier,id',
            'stok_minimal' => 'nullable|numeric',
            'harga_beli' => 'nullable|numeric',
            'harga_jual' => 'nullable|numeric',
            'saldo_awal_qty' => 'nullable|numeric',
            'saldo_awal_harga' => 'nullable|numeric',
            'barcode' => 'nullable|string|max:100',
            'lokasi_rak' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($data['tipe_produk'] != 'barang') {
            $data['saldo_awal_qty'] = 0;
            $data['saldo_awal_harga'] = 0;
            $data['stok'] = 0;
        } else {
            $data['stok'] = $data['saldo_awal_qty'] ?? 0;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        Produk::destroy($id);
        return back()->with('success', 'Produk berhasil dihapus');
    }

    public function downloadTemplate()
    {
        return Excel::download(new ProdukTemplateExport, 'template_produk.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'bulan_saldo_awal' => 'required|date_format:Y-m'
        ]);

        $file = $request->file('file');
        $bulan = $request->bulan_saldo_awal . '-01'; // Misal: '2025-06-01'
        $tanggalSaldoAwal = Carbon::parse($bulan)->startOfMonth()->toDateString();

        // Import produk dulu
        Excel::import(new ProdukImport, $file);

        // Ambil produk bertipe barang yang punya saldo awal qty > 0
        $produkList = Produk::where('tipe_produk', 'barang')
            ->where('saldo_awal_qty', '>', 0)
            ->get();

        foreach ($produkList as $produk) {
            $sudahAda = TransaksiPersediaan::where('kode_produk', $produk->kode_produk)
                ->where('jenis', 'saldo_awal')
                ->where('tanggal', $tanggalSaldoAwal)
                ->exists();

            if (!$sudahAda) {
                TransaksiPersediaan::create([
                    'kode_produk' => $produk->kode_produk,
                    'tanggal'     => $tanggalSaldoAwal,
                    'jenis'       => 'saldo_awal',
                    'qty'         => $produk->saldo_awal_qty,
                    'qty_sisa'    => $produk->saldo_awal_qty,
                    'harga'       => $produk->saldo_awal_harga ?? $produk->harga_beli ?? 0,
                    'sumber'      => 'Saldo awal import produk',
                ]);
            }
        }

        return back()->with('success', 'Data produk berhasil diimpor & saldo awal disimpan untuk bulan ' . $request->bulan_saldo_awal);
    }

    public function exportPdf()
    {
        $produk = Produk::with('kategori', 'supplier')->get();
        $pdf = Pdf::loadView('produk.pdf', compact('produk'));
        return $pdf->download('produk.pdf');
    }
}
