<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Kontrabon;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran;
use App\Models\PembelianInvoice;
use App\Models\PembayaranPembelian;

class PembayaranPembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = PembayaranPembelian::with('kontrabon.supplier')->latest();

        // Filter berdasarkan kata kunci faktur (nomor kontrabon)
        if ($request->filled('search')) {
            $query->whereHas('kontrabon', function ($q) use ($request) {
                $q->where('nomor_kontrabon', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $pembayaran = $query->get();

        return view('pembayaran_pembelian.index', compact('pembayaran'));
    }


    public function create()
    {
        $kontrabon = Kontrabon::where('status', '!=', 'lunas')->with('pembayaran')->get();
        $metodePembayaran = MetodePembayaran::all();

        return view('pembayaran_pembelian.create', compact('kontrabon', 'metodePembayaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kontrabon' => 'required|exists:kontrabon,id',
            'tanggal' => 'required|date',
            'metode' => 'required|string',
            'jumlah' => 'required|numeric|min:0.01',
        ]);

        // Ambil kontrabon beserta pembayaran dan detail (isi invoice)
        $kontrabon = Kontrabon::with(['pembayaran', 'details'])->findOrFail($request->id_kontrabon);

        // Hitung total pembayaran sebelumnya
        $totalPembayaranSebelumnya = $kontrabon->pembayaran->sum('jumlah');
        $totalPembayaranBaru = $totalPembayaranSebelumnya + $request->jumlah;

        // Validasi: total bayar tidak boleh melebihi total kontrabon
        if ($totalPembayaranBaru > $kontrabon->total) {
            return redirect()->back()
                ->withErrors(['jumlah' => 'Jumlah pembayaran melebihi total tagihan kontrabon.'])
                ->withInput();
        }

        // Simpan pembayaran baru
        PembayaranPembelian::create([
            'id_kontrabon' => $request->id_kontrabon,
            'tanggal' => $request->tanggal,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
        ]);

        // Tentukan status kontrabon baru
        if ($totalPembayaranBaru == $kontrabon->total) {
            $status = 'lunas';
        } elseif ($totalPembayaranBaru > 0) {
            $status = 'dicicil';
        } else {
            $status = 'belum_dibayar';
        }

        // Update status dan tanggal pembayaran kontrabon
        $kontrabon->update([
            'status' => $status,
            'tanggal_pembayaran' => $request->tanggal,
        ]);

        if ($status === 'lunas') {
            $invoiceIds = $kontrabon->details->pluck('id_invoice');

            PembelianInvoice::whereIn('id', $invoiceIds)->update([
                'status' => 'dibayar',
                'tanggal_pembayaran' => $request->tanggal, // opsional
            ]);
        }
        return redirect()->route('pembayaran-pembelian.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranPembelian::findOrFail($id);
        $kontrabon = $pembayaran->kontrabon;

        // Hapus pembayaran
        $pembayaran->delete();

        // Hitung ulang total pembayaran
        $totalBayar = $kontrabon->pembayaran->sum('jumlah');

        // Update status kontrabon
        if ($totalBayar == 0) {
            $kontrabon->update(['status' => 'belum_dibayar']);
        } elseif ($totalBayar < $kontrabon->total) {
            $kontrabon->update(['status' => 'dicicil']);
        } else {
            $kontrabon->update(['status' => 'lunas']);
        }

        // Ambil semua invoice yang terkait kontrabon ini
        $invoiceIds = $kontrabon->details->pluck('id_invoice');

        // Update status invoice jadi 'dikontrabon'
        \App\Models\PembelianInvoice::whereIn('id', $invoiceIds)->update([
            'status' => 'dikontrabon'
        ]);

        return redirect()->route('pembayaran-pembelian.index')->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
