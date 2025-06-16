<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $menu = [
            // Menu utama
            ['header' => 'MENU UTAMA'],

            [
                'text' => 'Dashboard',
                'url'  => 'dashboard',
                'icon' => 'fas fa-home',
            ],

            ['header' => 'MASTER DATA'],

            [
                'text'    => 'Master Data',
                'icon'    => 'fas fa-database',
                'submenu' => [
                    [
                        'text' => 'Customer',
                        'url'  => '/master/customer',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Supplier',
                        'url'  => '/master/supplier',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Produk',
                        'url'  => '/master/produk',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Chart of Account (COA)',
                        'url'  => '/master/akun',
                        'icon' => 'far fa-circle',
                    ],
                ],
            ],

            ['header' => 'TRANSAKSI'],

            [
                'text'    => 'Penjualan',
                'icon'    => 'fas fa-shopping-cart',
                'submenu' => [
                    [
                        'text' => 'Pesanan Penjualan',
                        'url'  => '/penjualan/pesanan',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Faktur Penjualan',
                        'url'  => '/penjualan/faktur',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Pengiriman Barang',
                        'url'  => '/penjualan/pengiriman',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Pembayaran',
                        'url'  => '/penjualan/pembayaran',
                        'icon' => 'far fa-circle',
                    ],
                ],
            ],

            [
                'text'    => 'Pembelian',
                'icon'    => 'fas fa-truck',
                'submenu' => [
                    [
                        'text' => 'Purchase Order',
                        'url'  => '/pembelian/po',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Penerimaan Barang',
                        'url'  => '/pembelian/penerimaan',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Faktur Pembelian',
                        'url'  => '/pembelian/faktur',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Pembayaran',
                        'url'  => '/pembelian/pembayaran',
                        'icon' => 'far fa-circle',
                    ],
                    [
                        'text' => 'Retur Pembelian',
                        'url'  => '/pembelian/retur',
                        'icon' => 'far fa-circle',
                    ],
                ],
            ],

            [
                'text' => 'Kas & Bank',
                'icon' => 'fas fa-wallet',
                'submenu' => [
                    ['text' => 'Arus Kas', 'url' => 'kas/arus-kas', 'icon' => 'far fa-circle'],
                    ['text' => 'Mutasi Kas', 'url' => 'kas/mutasi', 'icon' => 'far fa-circle'],
                    ['text' => 'Rekonsiliasi Bank', 'url' => 'kas/rekonsiliasi', 'icon' => 'far fa-circle'],
                ],
            ],

            [
                'text' => 'Persediaan',
                'icon' => 'fas fa-boxes',
                'submenu' => [
                    ['text' => 'Daftar Barang', 'url' => 'persediaan/barang', 'icon' => 'far fa-circle'],
                    ['text' => 'Mutasi Stok', 'url' => 'persediaan/mutasi', 'icon' => 'far fa-circle'],
                    ['text' => 'Penyesuaian Stok', 'url' => 'persediaan/penyesuaian', 'icon' => 'far fa-circle'],
                ],
            ],

            [
                'text' => 'Akuntansi',
                'icon' => 'fas fa-book',
                'submenu' => [
                    ['text' => 'Jurnal Umum', 'url' => 'akuntansi/jurnal', 'icon' => 'far fa-circle'],
                    ['text' => 'Buku Besar', 'url' => 'akuntansi/buku-besar', 'icon' => 'far fa-circle'],
                    ['text' => 'Neraca Saldo', 'url' => 'akuntansi/neraca-saldo', 'icon' => 'far fa-circle'],

                    [
                        'text'    => 'Laporan Keuangan',
                        'icon'    => 'fas fa-file-alt',
                        'submenu' => [
                            ['text' => 'Laba Rugi', 'url' => 'akuntansi/laporan/laba-rugi', 'icon' => 'far fa-circle'],
                            ['text' => 'Neraca', 'url' => 'akuntansi/laporan/neraca', 'icon' => 'far fa-circle'],
                            ['text' => 'Arus Kas', 'url' => 'akuntansi/laporan/arus-kas', 'icon' => 'far fa-circle'],
                            ['text' => 'Perubahan Modal', 'url' => 'akuntansi/laporan/perubahan-modal', 'icon' => 'far fa-circle'],
                        ],
                    ],
                ],
            ],

            [
                'text' => 'Penggajian',
                'icon' => 'fas fa-money-bill-wave',
                'submenu' => [
                    ['text' => 'Data Karyawan', 'url' => 'payroll/karyawan', 'icon' => 'far fa-circle'],
                    ['text' => 'Gaji & Potongan', 'url' => 'payroll/gaji', 'icon' => 'far fa-circle'],
                    ['text' => 'Slip Gaji', 'url' => 'payroll/slip', 'icon' => 'far fa-circle'],
                ],
            ],

            [
                'text' => 'Aset Tetap',
                'icon' => 'fas fa-warehouse',
                'submenu' => [
                    ['text' => 'Daftar Aset', 'url' => 'aset/daftar', 'icon' => 'far fa-circle'],
                    ['text' => 'Penyusutan', 'url' => 'aset/penyusutan', 'icon' => 'far fa-circle'],
                ],
            ],

            [
                'text' => 'Pengaturan',
                'icon' => 'fas fa-cogs',
                'submenu' => [
                    ['text' => 'Perusahaan', 'url' => 'setting/perusahaan', 'icon' => 'far fa-circle'],
                    ['text' => 'Cabang', 'url' => 'setting/cabang', 'icon' => 'far fa-circle'],
                    ['text' => 'Mata Uang', 'url' => 'setting/mata-uang', 'icon' => 'far fa-circle'],
                    ['text' => 'User & Hak Akses', 'url' => 'setting/user', 'icon' => 'far fa-circle'],
                ],
            ],
        ];

        view()->share('menu', $menu);
    }
}
