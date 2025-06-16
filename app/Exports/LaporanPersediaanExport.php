<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class LaporanPersediaanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama',
            'Satuan',
            'Kategori',
            'Saldo Awal Qty',
            'Saldo Awal Harga',
            'Saldo Awal Total',
            'Penerimaan Qty',
            'Penerimaan Harga',
            'Penerimaan Total',
            'Pengeluaran Qty',
            'Pengeluaran Harga',
            'Pengeluaran Total',
            'Saldo Akhir Qty',
            'Saldo Akhir Harga',
            'Saldo Akhir Total'
        ];
    }

    public function collection()
    {
        return Produk::with('kategori')->get()->map(function ($p) {
            $saldo_awal_qty   = $p->stok ?? 0;
            $saldo_awal_harga = $p->harga_beli ?? 0;
            $penerimaan_qty   = 0;
            $penerimaan_harga = 0;
            $pengeluaran_qty  = 0;
            $pengeluaran_harga = 0;
            $saldo_akhir_qty  = $saldo_awal_qty + $penerimaan_qty - $pengeluaran_qty;
            $saldo_akhir_harga = $saldo_awal_harga;

            return [
                $p->kode_produk,
                $p->nama,
                $p->satuan,
                $p->kategori->nama_kategori ?? '-',

                $saldo_awal_qty,
                $saldo_awal_harga,
                $saldo_awal_qty * $saldo_awal_harga,

                $penerimaan_qty,
                $penerimaan_harga,
                $penerimaan_qty * $penerimaan_harga,

                $pengeluaran_qty,
                $pengeluaran_harga,
                $pengeluaran_qty * $pengeluaran_harga,

                $saldo_akhir_qty,
                $saldo_akhir_harga,
                $saldo_akhir_qty * $saldo_akhir_harga,
            ];
        });
    }
}
