<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PembelianController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\PengeluaranAsetController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Gudang\SuratJalanController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Purchasing\DashboardController as PurchasingDashboard;
use App\Http\Controllers\Gudang\DashboardController as GudangDashboard;
use App\Http\Controllers\Gudang\PenerimaanController;
use App\Http\Controllers\Gudang\StokController;
use App\Http\Controllers\Gudang\PengeluaranBarangController;
use App\Http\Controllers\Sales\DashboardController as SalesDashboard;
use App\Http\Controllers\Sales\PermintaanController as SalesPermintaanController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboard;
use App\Http\Controllers\Pelanggan\PermintaanController as PelangganPermintaanController;
use App\Http\Controllers\Sawit\AdminDashboardController as SawitAdminDashboard;
use App\Http\Controllers\Sawit\AccountingDashboardController as SawitAccountingDashboard;
use App\Http\Controllers\Sawit\DirekturDashboardController as SawitDirekturDashboard;
use App\Http\Controllers\Sawit\BarangController as SawitBarangController;
use App\Http\Controllers\Sawit\PenjualanController as SawitPenjualanController;
use App\Http\Controllers\Sawit\PembelianController as SawitPembelianController;
use App\Http\Controllers\Sawit\SuratJalanController as SawitSuratJalanController;
use App\Http\Controllers\Sawit\PerusahaanController as SawitPerusahaanController;
use App\Http\Controllers\Sawit\PenjualController as SawitPenjualController;
use Illuminate\Support\Facades\Route;

Route::get('/favicon.ico', function () {
    $path = public_path('favicon.png');
    if (! is_file($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'public, max-age=604800',
    ]);
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes Middleware buat login
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware(['throttle:login'])
        ->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Notifications & Profile (semua role, harus login)
Route::middleware('auth')->group(function () {
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notif.read');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notif.readAll');

    // Profile & Settings
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('settings/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Barang (Master Data)
    Route::resource('barang', BarangController::class);

    // Data supplier (nama PT saja, tanpa akun login)
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Pembelian
    Route::get('pembelian/bukti/{path}', [PembelianController::class, 'buktiPembayaran'])
        ->where('path', '.*')
        ->name('pembelian.bukti');
    Route::get('pembelian/export', [PembelianController::class, 'export'])->name('pembelian.export');
    Route::resource('pembelian', PembelianController::class);
    Route::patch('pembelian/{pembelian}/status', [PembelianController::class, 'updateStatus'])->name('pembelian.status');

    // Pengeluaran Operasional
    Route::get('pengeluaran/bukti/{path}', [PengeluaranController::class, 'buktiFoto'])
        ->where('path', '.*')
        ->name('pengeluaran.bukti');
    Route::get('pengeluaran/export', [PengeluaranController::class, 'export'])->name('pengeluaran.export');
    Route::get('pengeluaran/aset', [PengeluaranAsetController::class, 'index'])->name('pengeluaran.aset.index');
    Route::get('pengeluaran/aset/{pengeluaranAset}/edit', [PengeluaranAsetController::class, 'edit'])->name('pengeluaran.aset.edit');
    Route::put('pengeluaran/aset/{pengeluaranAset}', [PengeluaranAsetController::class, 'update'])->name('pengeluaran.aset.update');
    Route::resource('pengeluaran', PengeluaranController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Invoice (admin)
    Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('invoice/export', [InvoiceController::class, 'export'])->name('invoice.export');
    Route::get('invoice/{permintaan}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::patch('invoice/{permintaan}/items/{detail}', [InvoiceController::class, 'updateItem'])->name('invoice.items.update');
    Route::get('invoice/{permintaan}/print', [InvoiceController::class, 'print'])->name('invoice.print');

    // Manajemen User
    Route::resource('users', UserController::class)->except(['show']);
    Route::patch('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
});

// Gudang routes
Route::middleware(['auth', 'role:gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangDashboard::class, 'index'])->name('dashboard');

    // Penerimaan Barang
    Route::get('penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
    Route::post('penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('penerimaan/{penerimaan}', [PenerimaanController::class, 'show'])->name('penerimaan.show');

    // Pengeluaran barang (stok keluar non-penjualan)
    Route::get('pengeluaran-barang', [PengeluaranBarangController::class, 'index'])->name('pengeluaran-barang.index');
    Route::get('pengeluaran-barang/create', [PengeluaranBarangController::class, 'create'])->name('pengeluaran-barang.create');
    Route::post('pengeluaran-barang', [PengeluaranBarangController::class, 'store'])->name('pengeluaran-barang.store');
    Route::get('pengeluaran-barang/{pengeluaran_barang}', [PengeluaranBarangController::class, 'show'])->name('pengeluaran-barang.show');

    // Stok Gudang
    Route::get('stok', [StokController::class, 'index'])->name('stok.index');
    Route::get('stok/{barang}', [StokController::class, 'show'])->name('stok.show');

    // Surat Jalan (gudang; admin tetap bisa akses lewat middleware role)
    Route::get('surat-jalan', [SuratJalanController::class, 'index'])->name('surat-jalan.index');
    Route::get('surat-jalan/create', [SuratJalanController::class, 'create'])->name('surat-jalan.create');
    Route::post('surat-jalan', [SuratJalanController::class, 'store'])->name('surat-jalan.store');
    Route::get('surat-jalan/{suratJalan}', [SuratJalanController::class, 'show'])->name('surat-jalan.show');
    Route::get('surat-jalan/{suratJalan}/print', [SuratJalanController::class, 'print'])->name('surat-jalan.print');
    Route::patch('surat-jalan/{suratJalan}/status', [SuratJalanController::class, 'updateStatus'])->name('surat-jalan.status');
});

// Sales routes
Route::middleware(['auth', 'role:sales'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/dashboard', [SalesDashboard::class, 'index'])->name('dashboard');

    // Permintaan Barang
    Route::get('permintaan', [SalesPermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('permintaan/create', [SalesPermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('permintaan', [SalesPermintaanController::class, 'store'])->name('permintaan.store');
    Route::get('permintaan/{permintaan}/edit', [SalesPermintaanController::class, 'edit'])->name('permintaan.edit');
    Route::put('permintaan/{permintaan}', [SalesPermintaanController::class, 'update'])->name('permintaan.update');
    Route::delete('permintaan/{permintaan}', [SalesPermintaanController::class, 'destroy'])->name('permintaan.destroy');
    Route::get('permintaan/{permintaan}', [SalesPermintaanController::class, 'show'])->name('permintaan.show');
    Route::patch('permintaan/{permintaan}/ceklis', [SalesPermintaanController::class, 'ceklis'])->name('permintaan.ceklis');
});

// Purchasing (PO & pembayaran + pembayaran pengeluaran)
Route::middleware(['auth', 'role:purchasing'])->prefix('purchasing')->name('purchasing.')->group(function () {
    Route::get('/dashboard', [PurchasingDashboard::class, 'index'])->name('dashboard');

    Route::get('pembelian/bukti/{path}', [PembelianController::class, 'buktiPembayaran'])
        ->where('path', '.*')
        ->name('pembelian.bukti');
    Route::get('pembelian/export', [PembelianController::class, 'export'])->name('pembelian.export');
    Route::resource('pembelian', PembelianController::class);
    Route::patch('pembelian/{pembelian}/status', [PembelianController::class, 'updateStatus'])->name('pembelian.status');

    Route::get('pengeluaran/bukti/{path}', [PengeluaranController::class, 'buktiFoto'])
        ->where('path', '.*')
        ->name('pengeluaran.bukti');
    Route::get('pengeluaran/export', [PengeluaranController::class, 'export'])->name('pengeluaran.export');
    Route::get('pengeluaran/aset', [PengeluaranAsetController::class, 'index'])->name('pengeluaran.aset.index');
    Route::get('pengeluaran/aset/{pengeluaranAset}/edit', [PengeluaranAsetController::class, 'edit'])->name('pengeluaran.aset.edit');
    Route::put('pengeluaran/aset/{pengeluaranAset}', [PengeluaranAsetController::class, 'update'])->name('pengeluaran.aset.update');
    Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('pengeluaran/{pengeluaran}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');
    Route::delete('pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
});

// Pelanggan routes
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');
});

// ========================================
// MODUL SAWIT - TERPISAH DARI SISTEM LAMA
// ========================================

// Admin Sawit routes
Route::middleware(['auth', 'role:adminsawit'])->prefix('sawit/admin')->name('sawit.admin.')->group(function () {
    Route::get('/dashboard', [SawitAdminDashboard::class, 'index'])->name('dashboard');
    
    // Master Data Barang Sawit
    Route::get('barang/export', [SawitBarangController::class, 'export'])->name('barang.export');
    Route::resource('barang', SawitBarangController::class);
    
    // Master Data Perusahaan (Pembeli)
    Route::resource('perusahaan', SawitPerusahaanController::class)->except(['show']);
    
    // Master Data Penjual
    Route::resource('penjual', SawitPenjualController::class)->except(['show']);
    
    // Penjualan Sawit
    Route::get('penjualan/export', [SawitPenjualanController::class, 'export'])->name('penjualan.export');
    Route::get('penjualan/{penjualan}/print', [SawitPenjualanController::class, 'print'])->name('penjualan.print');
    Route::resource('penjualan', SawitPenjualanController::class);
    
    // Pembelian Sawit
    Route::get('pembelian/export', [SawitPembelianController::class, 'export'])->name('pembelian.export');
    Route::resource('pembelian', SawitPembelianController::class);
    
    // Surat Jalan
    Route::get('surat-jalan/{suratJalan}/print', [SawitSuratJalanController::class, 'print'])->name('surat-jalan.print');
    Route::get('surat-jalan/penjualan/{id}', [SawitSuratJalanController::class, 'getPenjualan'])->name('surat-jalan.get-penjualan');
    Route::resource('surat-jalan', SawitSuratJalanController::class)->except(['edit', 'update']);
});

// Accounting Sawit routes
Route::middleware(['auth', 'role:accountingsawit'])->prefix('sawit/accounting')->name('sawit.accounting.')->group(function () {
    Route::get('/dashboard', [SawitAccountingDashboard::class, 'index'])->name('dashboard');
    
    // TODO: Tambahkan route untuk fitur accounting sawit
    // Contoh:
    // Route::resource('pembayaran-petani', PembayaranPetaniController::class);
    // Route::resource('pengeluaran', PengeluaranSawitController::class);
    // Route::get('laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.index');
});

// Direktur Sawit routes (Read-Only)
Route::middleware(['auth', 'role:direktursawit'])->prefix('sawit/direktur')->name('sawit.direktur.')->group(function () {
    Route::get('/dashboard', [SawitDirekturDashboard::class, 'index'])->name('dashboard');
    
    // TODO: Tambahkan route untuk laporan direktur sawit (read-only)
    // Contoh:
    // Route::get('laporan-produksi', [LaporanProduksiController::class, 'index'])->name('laporan.produksi');
    // Route::get('laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    // Route::get('laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');
});
