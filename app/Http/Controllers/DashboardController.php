<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Penjualan
        $totalPenjualan = SalesOrder::sum('total');

        // Total Purchase Order
        $totalPO = PurchaseOrder::sum('total');

        // Total Piutang: invoice penjualan yang belum lunas
        $totalPiutang = DB::table('sales_invoice')
            ->leftJoin('pembayaran_penjualan', 'sales_invoice.id', '=', 'pembayaran_penjualan.id_invoice')
            ->select('sales_invoice.id', 'sales_invoice.total', DB::raw('COALESCE(SUM(pembayaran_penjualan.jumlah),0) as total_bayar'))
            ->groupBy('sales_invoice.id', 'sales_invoice.total')
            ->get()
            ->filter(function ($item) {
                return $item->total > $item->total_bayar;
            })
            ->sum(function ($item) {
                return $item->total - $item->total_bayar;
            });

        // Total Hutang: invoice pembelian yang belum lunas
        $totalHutang = DB::table('pembelian_invoice')
            ->leftJoin('pembayaran_pembelian', 'pembelian_invoice.id', '=', 'pembayaran_pembelian.id_kontrabon')
            ->select('pembelian_invoice.id', 'pembelian_invoice.total', DB::raw('COALESCE(SUM(pembayaran_pembelian.jumlah),0) as total_bayar'))
            ->groupBy('pembelian_invoice.id', 'pembelian_invoice.total')
            ->get()
            ->filter(function ($item) {
                return $item->total > $item->total_bayar;
            })
            ->sum(function ($item) {
                return $item->total - $item->total_bayar;
            });

        // Kartu ringkasan
        $cards = [
            [
                'title' => 'Total Penjualan',
                'value' => $totalPenjualan,
                'color' => 'primary',
                'icon' => 'shopping-cart',
                'url' => 'sales_order',
            ],
            [
                'title' => 'Purchase Order',
                'value' => $totalPO,
                'color' => 'success',
                'icon' => 'file-invoice-dollar',
                'url' => 'purchase-order',
            ],
            [
                'title' => 'Piutang',
                'value' => $totalPiutang,
                'color' => 'warning',
                'icon' => 'hand-holding-usd',
                'url' => 'piutang',
            ],
            [
                'title' => 'Hutang',
                'value' => $totalHutang,
                'color' => 'danger',
                'icon' => 'credit-card',
                'url' => 'hutang-supplier',
            ],
        ];

        return view('dashboard', compact(
            'totalPenjualan',
            'totalPO',
            'totalPiutang',
            'totalHutang',
            'cards',
        ));
    }
}
