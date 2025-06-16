<?php

namespace App\Http\Controllers;

use App\Models\FakturPajakMasukan;
use App\Models\PembelianInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FakturPajakMasukanController extends Controller
{
    public function index()
    {
        $ppnMasukan = FakturPajakMasukan::with(['invoice.penerimaan.purchaseOrder.supplier'])->get();

        return view('faktur_pajak_masukan.index', compact('ppnMasukan'));
    }
}
