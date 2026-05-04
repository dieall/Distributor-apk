<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\PembelianController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\SupplierController;
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

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware(['throttle:login'])
        ->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Notifications (semua role, harus login)
Route::middleware('auth')->group(function () {
    Route::patch('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notif.read');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notif.readAll');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Barang (Master Data)
    Route::resource('barang', BarangController::class);

    // Data supplier (nama PT saja, tanpa akun login)
    Route::resource('suppliers', SupplierController::class)->except(['show']);

    // Pembelian
    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::patch('pembelian/{pembelian}/status', [PembelianController::class, 'updateStatus'])->name('pembelian.status');

    // Pengeluaran Operasional
    Route::get('pengeluaran/export', [PengeluaranController::class, 'export'])->name('pengeluaran.export');
    Route::resource('pengeluaran', PengeluaranController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Invoice (admin)
    Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('invoice/export', [InvoiceController::class, 'export'])->name('invoice.export');
    Route::get('invoice/{permintaan}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('invoice/{permintaan}/print', [InvoiceController::class, 'print'])->name('invoice.print');
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
    Route::get('permintaan/{permintaan}', [SalesPermintaanController::class, 'show'])->name('permintaan.show');
    Route::patch('permintaan/{permintaan}/ceklis', [SalesPermintaanController::class, 'ceklis'])->name('permintaan.ceklis');
});

// Purchasing (PO & pembayaran + pembayaran pengeluaran)
Route::middleware(['auth', 'role:purchasing'])->prefix('purchasing')->name('purchasing.')->group(function () {
    Route::get('/dashboard', [PurchasingDashboard::class, 'index'])->name('dashboard');

    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    Route::get('pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show');
    Route::patch('pembelian/{pembelian}/status', [PembelianController::class, 'updateStatus'])->name('pembelian.status');

    Route::get('pengeluaran/export', [PengeluaranController::class, 'export'])->name('pengeluaran.export');
    Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
    Route::post('pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::get('pengeluaran/{pengeluaran}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');
    Route::delete('pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
});

// Pelanggan routes
Route::middleware(['auth', 'role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganDashboard::class, 'index'])->name('dashboard');

    // Permintaan Barang
    Route::get('permintaan', [PelangganPermintaanController::class, 'index'])->name('permintaan.index');
    Route::get('permintaan/create', [PelangganPermintaanController::class, 'create'])->name('permintaan.create');
    Route::post('permintaan', [PelangganPermintaanController::class, 'store'])->name('permintaan.store');
    Route::get('permintaan/{permintaan}', [PelangganPermintaanController::class, 'show'])->name('permintaan.show');
});
