<?php

use App\Models\ProdukGudang;
use FontLib\Table\Type\name;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\KontrabonController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\JurnalUmumController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\MultiCabangController;
use App\Http\Controllers\NeracaLajurController;
use App\Http\Controllers\NeracaSaldoController;
use App\Http\Controllers\PpnKeluaranController;
use App\Http\Controllers\ProdukGudangController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\MappingJurnalController;
use App\Http\Controllers\MultiCurrencyController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\HutangSupplierController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PembelianInvoiceController;
use App\Http\Controllers\JurnalPenyesuaianController;
use App\Http\Controllers\LaporanPersediaanController;
use App\Http\Controllers\FakturPajakMasukanController;
use App\Http\Controllers\PembayaranPembelianController;
use App\Http\Controllers\PembayaranPenjualanController;
use App\Http\Controllers\PenerimaanPembelianController;
use App\Http\Controllers\PengirimanPenjualanController;

// Redirect root ke login kalau belum auth, atau dashboard kalau sudah
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth routes (login, logout)
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Semua route di bawah ini harus login (middleware 'auth')
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Penjualan
    Route::prefix('penjualan')->name('penjualan.')->group(function () {
        Route::get('pesanan', [PenjualanController::class, 'pesanan'])->name('pesanan');
        Route::get('faktur', [PenjualanController::class, 'faktur'])->name('faktur');
        Route::get('pengiriman', [PenjualanController::class, 'pengiriman'])->name('pengiriman');
        Route::get('pembayaran', [PenjualanController::class, 'pembayaran'])->name('pembayaran');
    });


    // Inventory Movement
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Payroll
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');

    // Fixed Asset
    Route::get('fixed-asset', [FixedAssetController::class, 'index'])->name('fixedasset.index');

    // Multi-Cabang
    Route::get('multi-cabang', [MultiCabangController::class, 'index'])->name('multicabang.index');

    // Multi-Currency
    Route::get('multi-currency', [MultiCurrencyController::class, 'index'])->name('multicurrency.index');
});
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('index');
    Route::get('/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/', [CustomerController::class, 'store'])->name('store');
    Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');

    // Tambahan khusus import dan download template
    Route::post('/import', [CustomerController::class, 'import'])->name('import');
    Route::get('/download-template', [CustomerController::class, 'downloadTemplate'])->name('downloadTemplate');
});
Route::prefix('coa')->group(function () {
    Route::get('/', [CoaController::class, 'index'])->name('coa.index');
    Route::get('/create', [CoaController::class, 'create'])->name('coa.create');
    Route::post('/store', [CoaController::class, 'store'])->name('coa.store');
    Route::get('/edit/{kode_akun}', [CoaController::class, 'edit'])->name('coa.edit');
    Route::put('/update/{kode_akun}', [CoaController::class, 'update'])->name('coa.update');
    Route::delete('/delete/{kode_akun}', [CoaController::class, 'destroy'])->name('coa.destroy');
    Route::delete('/bulk-delete', [CoaController::class, 'bulkDelete'])->name('coa.bulkDelete');


    Route::get('/download-template', [CoaController::class, 'downloadTemplate'])->name('coa.downloadTemplate');
    Route::get('/import', [CoaController::class, 'importForm'])->name('coa.importForm');
    Route::post('/import', [CoaController::class, 'import'])->name('coa.import');

    // Route tambahan untuk menampilkan COA dalam tree view
    Route::get('/tree', [CoaController::class, 'tree'])->name('coa.tree');
});
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Import Excel
    Route::post('/import', [SupplierController::class, 'import'])->name('supplier.import');
    // Download template Excel
    Route::get('/download-template', [SupplierController::class, 'downloadTemplate'])->name('supplier.downloadTemplate');
});

Route::prefix('produk')->name('produk.')->group(function () {
    Route::get('/', [ProdukController::class, 'index'])->name('index');
    Route::get('/create', [ProdukController::class, 'create'])->name('create');
    Route::post('/', [ProdukController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProdukController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProdukController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProdukController::class, 'destroy'])->name('destroy');
    Route::get('/download-template', [ProdukController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::post('/import', [ProdukController::class, 'import'])->name('import');
    Route::get('/pdf', [ProdukController::class, 'exportPdf'])->name('exportPdf');
});
Route::resource('kategori-produk', KategoriProdukController::class)->names([
    'index' => 'kategori-produk.index',
    'create' => 'kategori-produk.create',
    'store' => 'kategori-produk.store',
    'edit' => 'kategori-produk.edit',
    'update' => 'kategori-produk.update',
    'destroy' => 'kategori-produk.destroy',
]);
Route::resource('kategori-produk', KategoriProdukController::class)->except(['show']);

Route::resource('gudang', GudangController::class);
Route::get('gudang/template/export', [GudangController::class, 'exportTemplate'])->name('gudang.template.export');
Route::post('gudang/import', [GudangController::class, 'importGudang'])->name('gudang.import');

Route::prefix('produk-gudang')->name('produk-gudang.')->group(function () {
    Route::get('/', [ProdukGudangController::class, 'index'])->name('index');
    Route::get('/create', [ProdukGudangController::class, 'create'])->name('create'); // Form tambah
    Route::post('/', [ProdukGudangController::class, 'store'])->name('store'); // Simpan data baru
    Route::get('/{id}/edit', [ProdukGudangController::class, 'edit'])->name('edit'); // Form edit
    Route::put('/{id}', [ProdukGudangController::class, 'update'])->name('update'); // Update data
    Route::delete('/{id}', [ProdukGudangController::class, 'destroy'])->name('destroy'); // Hapus dat
    Route::get('import', [ProdukGudangController::class, 'importForm'])->name('import.form');
    Route::post('import', [ProdukGudangController::class, 'import'])->name('import');
    Route::get('export-template', [ProdukGudangController::class, 'exportTemplate'])->name('export-template');
});

Route::prefix('mapping_jurnal')->name('mapping_jurnal.')->group(function () {
    Route::get('/', [MappingJurnalController::class, 'index'])->name('index'); // List semua mapping
    Route::get('/create', [MappingJurnalController::class, 'create'])->name('create'); // Form tambah
    Route::post('/', [MappingJurnalController::class, 'store'])->name('store'); // Simpan data baru
    Route::get('/{id}/edit', [MappingJurnalController::class, 'edit'])->name('edit'); // Form edit
    Route::put('/{id}', [MappingJurnalController::class, 'update'])->name('update'); // Update data
    Route::delete('/{id}', [MappingJurnalController::class, 'destroy'])->name('destroy'); // Hapus data
    Route::get('/{id}', [MappingJurnalController::class, 'show'])->name('show'); // Tampilkan detail (opsional)
    Route::post('/import', [MappingJurnalController::class, 'import'])->name('import');
    Route::get('/template', [MappingJurnalController::class, 'downloadTemplate'])->name('downloadTemplate');
    Route::resource('mapping_jurnal', MappingJurnalController::class)->except(['show']);
});

Route::get('/sales_order', [SalesOrderController::class, 'index'])->name('sales_order.index');
Route::get('/sales_order/create', [SalesOrderController::class, 'create'])->name('sales_order.create');
Route::post('/sales_order', [SalesOrderController::class, 'store'])->name('sales_order.store');
Route::get('/sales_order/{id}', [SalesOrderController::class, 'show'])->name('sales_order.show');
Route::get('/sales_order/{id}/edit', [SalesOrderController::class, 'edit'])->name('sales_order.edit');
Route::put('/sales_order/{id}', [SalesOrderController::class, 'update'])->name('sales_order.update');
Route::delete('/sales_order/{id}', [SalesOrderController::class, 'destroy'])->name('sales_order.destroy');
Route::get('sales_order/export', [SalesOrderController::class, 'export'])->name('sales_order.export');
Route::patch('/sales_order/{id}/reject', [SalesOrderController::class, 'reject'])->name('sales_order.reject');

Route::resource('sales-invoice', SalesInvoiceController::class)->only([
    'index',
    'create',
    'store',
    'show'
]);

// Route tambahan untuk printPdf
Route::get('sales-invoice/{id}/print-pdf', [SalesInvoiceController::class, 'printPdf'])
    ->name('sales-invoice.printPdf');
Route::delete('sales-invoice/{id}', [SalesInvoiceController::class, 'destroy'])->name('sales-invoice.destroy');
Route::post('sales-invoice/{id}/cancel', [SalesInvoiceController::class, 'cancel'])->name('sales-invoice.cancel');


Route::middleware(['auth'])->prefix('pengiriman-penjualan')->name('pengiriman-penjualan.')->group(function () {
    Route::get('/', [PengirimanPenjualanController::class, 'index'])->name('index');
    Route::get('/create', [PengirimanPenjualanController::class, 'create'])->name('create');
    Route::post('/', [PengirimanPenjualanController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PengirimanPenjualanController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PengirimanPenjualanController::class, 'update'])->name('update');
    Route::delete('/{id}', [PengirimanPenjualanController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/cetak', [PengirimanPenjualanController::class, 'cetakSuratJalan'])->name('cetak-pdf');
    Route::get('/{id}', [PengirimanPenjualanController::class, 'show'])->name('show');
    Route::patch('/{id}/update-status', [PengirimanPenjualanController::class, 'updateStatus'])->name('update-status'); // ✅ Fix di sini
});


Route::prefix('pembayaran-penjualan')->name('pembayaran-penjualan.')->group(function () {
    Route::get('/', [PembayaranPenjualanController::class, 'index'])->name('index');
    Route::get('/create', [PembayaranPenjualanController::class, 'create'])->name('create');
    Route::post('/', [PembayaranPenjualanController::class, 'store'])->name('store');
    Route::delete('/{id}', [PembayaranPenjualanController::class, 'destroy'])->name('destroy');
    Route::get('/invoice-total/{id}', [PembayaranPenjualanController::class, 'getInvoiceTotal']);
    Route::get('/invoice-produk-belum-bayar/{id}', [PembayaranPenjualanController::class, 'getProdukBelumBayar']);
    Route::post('/{id}', [PembayaranPenjualanController::class, 'batal'])->name('batal');

    Route::get('/{id}', [PembayaranPenjualanController::class, 'show'])->name('show');
    Route::get('/{id}/cetak-pdf', [PembayaranPenjualanController::class, 'cetakPdf'])->name('cetakPdf');
});

Route::group(['prefix' => 'metode-pembayaran', 'as' => 'metode-pembayaran.'], function () {
    Route::get('/', [MetodePembayaranController::class, 'index'])->name('index');

    Route::get('/create', [MetodePembayaranController::class, 'create'])->name('create');
    Route::post('/', [MetodePembayaranController::class, 'store'])->name('store');
    Route::get('/{id}', [MetodePembayaranController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [MetodePembayaranController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MetodePembayaranController::class, 'update'])->name('update');
    Route::delete('/{id}', [MetodePembayaranController::class, 'destroy'])->name('destroy');
});

Route::get('/ppn-keluaran', [PpnKeluaranController::class, 'index'])->name('ppn.keluaran.index');
Route::get('/ppn-keluaran/export-excel', [PpnKeluaranController::class, 'exportExcel'])->name('ppn.keluaran.exportExcel');
Route::get('/ppn-keluaran/export-pdf', [PpnKeluaranController::class, 'exportPDF'])->name('ppn.keluaran.exportPDF');

Route::prefix('piutang')->group(function () {
    Route::get('/', [PiutangController::class, 'index'])->name('piutang.index');
    Route::get('/daftar', [PiutangController::class, 'daftar'])->name('piutang.daftar');

    // Halaman daftar invoice untuk pilih bayar
    Route::get('/bayar', [PiutangController::class, 'bayarCreate'])->name('piutang.bayarCreate');

    Route::get('/export/pdf', [PiutangController::class, 'exportPdf'])->name('piutang.exportPdf');
    Route::get('/export-excel', [PiutangController::class, 'exportExcel'])->name('piutang.exportExcel');
});
Route::prefix('akuntansi')->group(function () {
    Route::get('jurnal_umum', [JurnalUmumController::class, 'index'])->name('jurnal_umum.index');
    Route::get('jurnal_umum/create', [JurnalUmumController::class, 'create'])->name('jurnal_umum.create');
    Route::post('jurnal_umum', [JurnalUmumController::class, 'store'])->name('jurnal_umum.store');

    Route::get('jurnal_umum/{id}/edit', [JurnalUmumController::class, 'edit'])->name('jurnal_umum.edit');
    Route::put('jurnal_umum/{id}', [JurnalUmumController::class, 'update'])->name('jurnal_umum.update');

    Route::delete('jurnal_umum/{id}', [JurnalUmumController::class, 'destroy'])->name('jurnal_umum.destroy');
    Route::delete('jurnal_umum/bulk-destroy', [JurnalUmumController::class, 'bulkDestroy'])->name('jurnal_umum.bulkDestroy');
});

Route::prefix('akuntansi')->group(function () {
    Route::get('/buku_besar', [BukuBesarController::class, 'index'])->name('buku_besar.index');
    Route::get('/buku_besar/export_excel', [BukuBesarController::class, 'exportExcel'])->name('buku_besar.export_excel');
    Route::get('/buku_besar/export_pdf', [BukuBesarController::class, 'exportPDF'])->name('buku_besar.export_pdf');
});


Route::prefix('akuntansi')->group(function () {
    Route::get('/neraca_saldo', [NeracaSaldoController::class, 'index'])->name('neraca_saldo.index');
    Route::get('/neraca-saldo/export', [NeracaSaldoController::class, 'exportExcel'])->name('neraca.exportExcel');
    Route::get('/neraca-lajur', [NeracaLajurController::class, 'index'])->name('neraca_lajur.index');
    Route::get('/neraca-lajur/export', [NeracaLajurController::class, 'export'])->name('neraca-lajur.export');
});

Route::prefix('akuntansi/jurnal_penyesuaian')->name('jurnal-penyesuaian.')->group(function () {
    Route::get('/', [JurnalPenyesuaianController::class, 'index'])->name('index');
    Route::get('/create', [JurnalPenyesuaianController::class, 'create'])->name('create');
    Route::post('/store', [JurnalPenyesuaianController::class, 'store'])->name('store');
    Route::delete('/{id}', [JurnalPenyesuaianController::class, 'destroy'])->name('destroy');
});

Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
Route::get('/laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
Route::get('/laporan/arus-kas', [LaporanController::class, 'arusKasLangsung'])->name('laporan.arus-kas');
Route::get('/laporan/perubahan-modal', [LaporanController::class, 'perubahanModal'])->name('laporan.perubahan-modal');
Route::get('/laporan/arus-kas/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.arus-kas.pdf');
Route::get('/laporan/neraca/export', [LaporanController::class, 'exportNeracaExcel'])->name('laporan.neraca.export');
Route::post('/laporan/neraca/closing', [LaporanController::class, 'closingNeraca'])->name('laporan.neraca.closing');
Route::get('/laporan/neraca/batal-closing', [LaporanController::class, 'batalClosing'])->name('laporan.neraca.batalClosing');

Route::prefix('purchase-order')->name('purchase-order.')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
    Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
    Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
    Route::get('/{id}', [PurchaseOrderController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PurchaseOrderController::class, 'update'])->name('update');
    Route::delete('/{id}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
});

Route::resource('penerimaan', PenerimaanPembelianController::class);

Route::resource('pembelian-invoice', PembelianInvoiceController::class);
// Route untuk ambil penerimaan + produk
Route::get('/get-produk-po/{id}', [PenerimaanPembelianController::class, 'getProdukPO']);
// Route API untuk ambil data penerimaan barang per ID (gunakan controller, bukan closure)
Route::get('/api/penerimaan/{id}', [PenerimaanPembelianController::class, 'getPenerimaanJson']);


Route::get('/api/penerimaan/{id}', [PembelianInvoiceController::class, 'getPenerimaan']);
Route::put('/pembelian-invoice/{id}/batal', [PembelianInvoiceController::class, 'batal'])->name('pembelian-invoice.batal');

Route::get('/faktur-pajak-masukan', [FakturPajakMasukanController::class, 'index'])->name('faktur-pajak-masukan.index');

Route::get('/kontrabon/get-invoices', [KontrabonController::class, 'getInvoicesBySupplier'])->name('kontrabon.getInvoicesBySupplier');
Route::resource('kontrabon', KontrabonController::class)->only(['index', 'create', 'store', 'show']);
Route::post('kontrabon/{id}/batal', [KontrabonController::class, 'batal'])->name('kontrabon.batal');
Route::get('/kontrabon/{id}/cetak', [KontrabonController::class, 'cetak'])->name('kontrabon.cetak');


Route::prefix('pembayaran-pembelian')->name('pembayaran-pembelian.')->group(function () {
    Route::get('/', [PembayaranPembelianController::class, 'index'])->name('index');
    Route::get('/create', [PembayaranPembelianController::class, 'create'])->name('create');
    Route::post('/', [PembayaranPembelianController::class, 'store'])->name('store');
    Route::get('/{id}', [PembayaranPembelianController::class, 'show'])->name('show');
    Route::delete('/{id}', [PembayaranPembelianController::class, 'destroy'])->name('destroy');
});

Route::prefix('hutang-supplier')->name('hutang-supplier.')->group(function () {
    Route::get('/', [HutangSupplierController::class, 'index'])->name('index');
    Route::get('/export-excel', [HutangSupplierController::class, 'exportExcel'])->name('exportExcel');
    Route::get('/export-pdf', [HutangSupplierController::class, 'exportPdf'])->name('exportPdf');
});

Route::prefix('retur-pembelian')->name('retur-pembelian.')->group(function () {
    Route::get('/', [ReturPembelianController::class, 'index'])->name('index');
    Route::get('/create', [ReturPembelianController::class, 'create'])->name('create');
    Route::post('/', [ReturPembelianController::class, 'store'])->name('store');
    Route::get('/{id}', [ReturPembelianController::class, 'show'])->name('show');
    Route::get('/{id}/print', [ReturPembelianController::class, 'print'])->name('print');
    Route::delete('/{id}', [ReturPembelianController::class, 'destroy'])->name('destroy');
});
Route::get('/laporan-persediaan', [LaporanPersediaanController::class, 'index']);
Route::get('laporan-persediaan', [LaporanPersediaanController::class, 'index'])->name('laporan-persediaan.index');
Route::get('laporan-persediaan/export', [LaporanPersediaanController::class, 'export'])->name('laporan-persediaan.export');
Route::post('laporan-persediaan/closing', [LaporanPersediaanController::class, 'closing'])->name('laporan-persediaan.closing');
Route::post('/laporan-persediaan/closing-manual', [LaporanPersediaanController::class, 'closingByDate'])->name('laporan-persediaan.closing.manual');

Route::get('/kartu-stok', [KartuStokController::class, 'index'])->name('kartu-stok.index');
Route::get('/kartu-stok/export-excel', [KartuStokController::class, 'exportExcel'])->name('kartu-stok.export.excel');



require __DIR__ . '/auth.php';
