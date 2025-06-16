<?php

namespace App\Http\Controllers;

class PenjualanController extends Controller
{
    public function pesanan()
    {
        return view('penjualan.pesanan');
    }

    public function faktur()
    {
        return view('penjualan.faktur');
    }

    public function pengiriman()
    {
        return view('penjualan.pengiriman');
    }

    public function pembayaran()
    {
        return view('penjualan.pembayaran');
    }
}
