# Modul Sawit - Dokumentasi

## 📋 Deskripsi

Modul Sawit adalah sistem terpisah yang ditambahkan ke aplikasi distributor untuk mengelola bisnis sawit (TBS, CPO, dll). Modul ini **sepenuhnya terpisah** dari sistem lama dan tidak mengganggu fitur, role, maupun database yang sudah ada.

## 🎯 Fitur Utama

### 3 Role Baru:
1. **Admin Sawit** (`adminsawit`)
   - Akses penuh untuk mengelola transaksi sawit
   - Dashboard khusus dengan statistik sawit
   - Dapat mengelola pembelian TBS, produksi CPO, penjualan

2. **Accounting Sawit** (`accountingsawit`)
   - Mengelola keuangan sawit
   - Pembayaran petani
   - Pengeluaran operasional
   - Laporan keuangan

3. **Direktur Sawit** (`direktursawit`)
   - Akses **READ-ONLY** ke semua data sawit
   - Dashboard monitoring
   - Laporan produksi, penjualan, dan keuangan

## 🗂️ Struktur File

### Controllers
```
app/Http/Controllers/Sawit/
├── AdminDashboardController.php
├── AccountingDashboardController.php
└── DirekturDashboardController.php
```

### Views
```
resources/views/
├── layouts/
│   └── sawit.blade.php (Layout khusus modul sawit)
└── sawit/
    ├── partials/
    │   ├── sidebar.blade.php (Sidebar khusus sawit)
    │   └── navbar.blade.php (Navbar khusus sawit)
    ├── admin/
    │   └── dashboard.blade.php
    ├── accounting/
    │   └── dashboard.blade.php
    └── direktur/
        └── dashboard.blade.php
```

### Models
- Menggunakan model `User` yang sudah ada
- Ditambahkan 3 helper methods:
  - `isAdminSawit()`
  - `isAccountingSawit()`
  - `isDirekturSawit()`

### Routes
```
routes/web.php
```
Semua route sawit dikelompokkan dengan prefix:
- `/sawit/admin/*` untuk Admin Sawit
- `/sawit/accounting/*` untuk Accounting Sawit
- `/sawit/direktur/*` untuk Direktur Sawit

### Middleware
- `RoleMiddleware` sudah diupdate untuk support role sawit
- Direktur Sawit memiliki akses read-only (hanya GET/HEAD/OPTIONS)

### Database
- Migration: `2026_05_15_000001_add_sawit_roles_to_users.php`
- Menambahkan 3 role baru ke enum `users.role`:
  - `adminsawit`
  - `accountingsawit`
  - `direktursawit`

## 🔐 User Demo

Setelah menjalankan seeder, tersedia 3 user demo:

| Role | Email | Password |
|------|-------|----------|
| Admin Sawit | adminsawit@example.com | password |
| Accounting Sawit | accountingsawit@example.com | password |
| Direktur Sawit | direktursawit@example.com | password |

## 🚀 Cara Menggunakan

### 1. Login
- Akses halaman login: `/login`
- Gunakan salah satu kredensial di atas
- Sistem akan otomatis redirect ke dashboard sesuai role

### 2. Dashboard
Setiap role memiliki dashboard terpisah:
- Admin Sawit: `/sawit/admin/dashboard`
- Accounting Sawit: `/sawit/accounting/dashboard`
- Direktur Sawit: `/sawit/direktur/dashboard`

### 3. Menambahkan Fitur Baru

#### Contoh: Menambahkan Fitur Pembelian TBS

**1. Buat Controller:**
```php
// app/Http/Controllers/Sawit/PembelianTbsController.php
<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;

class PembelianTbsController extends Controller
{
    public function index()
    {
        return view('sawit.admin.pembelian-tbs.index');
    }
    
    // ... method lainnya
}
```

**2. Tambahkan Route:**
```php
// routes/web.php
Route::middleware(['auth', 'role:adminsawit'])->prefix('sawit/admin')->name('sawit.admin.')->group(function () {
    Route::resource('pembelian-tbs', PembelianTbsController::class);
});
```

**3. Buat View:**
```php
// resources/views/sawit/admin/pembelian-tbs/index.blade.php
@extends('layouts.sawit')
@section('title', 'Pembelian TBS')

@section('content')
    <!-- Konten halaman -->
@endsection
```

**4. Update Sidebar:**
Edit `resources/views/sawit/partials/sidebar.blade.php` dan tambahkan menu baru.

## 📊 Database Schema

### Tabel Users (Updated)
```sql
role ENUM(
    'admin', 
    'direktur', 
    'gudang', 
    'sales', 
    'purchasing', 
    'pelanggan',
    'adminsawit',        -- BARU
    'accountingsawit',   -- BARU
    'direktursawit'      -- BARU
)
```

### Tabel Baru (Contoh untuk fitur sawit)
Anda bisa membuat tabel baru sesuai kebutuhan, misalnya:
- `pembelian_tbs` - Data pembelian TBS dari petani
- `produksi_cpo` - Data produksi CPO
- `penjualan_sawit` - Data penjualan CPO
- `petani` - Master data petani
- `pembeli_sawit` - Master data pembeli CPO

## ⚠️ Catatan Penting

### ✅ Yang AMAN Dilakukan:
1. Menambahkan controller baru di folder `Sawit/`
2. Menambahkan view baru di folder `sawit/`
3. Menambahkan route baru dengan prefix `sawit/`
4. Membuat tabel database baru untuk modul sawit
5. Menambahkan model baru untuk entitas sawit

### ❌ Yang TIDAK BOLEH Dilakukan:
1. **JANGAN** mengubah controller sistem lama (Admin, Gudang, Sales, dll)
2. **JANGAN** mengubah view sistem lama
3. **JANGAN** mengubah route sistem lama
4. **JANGAN** mengubah tabel database sistem lama
5. **JANGAN** menghapus role lama dari enum

## 🔒 Keamanan & Isolasi

### Middleware Protection
- Setiap route sawit dilindungi dengan middleware `role`
- Admin Sawit hanya bisa akses route dengan role `adminsawit`
- Accounting Sawit hanya bisa akses route dengan role `accountingsawit`
- Direktur Sawit hanya bisa akses route dengan role `direktursawit` (read-only)

### Read-Only untuk Direktur Sawit
Direktur Sawit memiliki batasan:
- ✅ Bisa akses semua halaman sawit (GET)
- ❌ Tidak bisa create, update, delete data
- ✅ Bisa logout

### Isolasi Data
- Sistem lama dan modul sawit menggunakan tabel database yang berbeda
- Tidak ada foreign key yang menghubungkan data lama dengan data sawit
- Jika modul sawit error, sistem lama tetap berjalan normal

## 🛠️ Maintenance

### Menambah User Sawit Baru
```php
// Via Tinker
php artisan tinker

User::create([
    'name' => 'Nama User',
    'email' => 'email@example.com',
    'password' => Hash::make('password'),
    'role' => 'adminsawit', // atau accountingsawit, direktursawit
    'is_active' => true,
]);
```

### Atau via Admin Panel
Admin dapat menambahkan user sawit melalui menu "Manajemen User" dengan memilih role sawit yang sesuai.

## 📝 TODO / Pengembangan Selanjutnya

Berikut fitur yang bisa dikembangkan untuk modul sawit:

### Admin Sawit:
- [ ] CRUD Pembelian TBS (Tandan Buah Segar)
- [ ] CRUD Produksi CPO (Crude Palm Oil)
- [ ] CRUD Penjualan CPO
- [ ] Master Data Petani
- [ ] Master Data Pembeli
- [ ] Laporan Harian Produksi

### Accounting Sawit:
- [ ] Pembayaran ke Petani
- [ ] Pengeluaran Operasional
- [ ] Laporan Keuangan (Laba/Rugi)
- [ ] Laporan Cashflow
- [ ] Approval Pembayaran

### Direktur Sawit:
- [ ] Dashboard Analytics (Chart produksi, penjualan)
- [ ] Laporan Produksi Bulanan
- [ ] Laporan Penjualan Bulanan
- [ ] Laporan Keuangan Bulanan
- [ ] Export ke Excel/PDF

## 🆘 Troubleshooting

### Error: "Anda tidak memiliki akses ke halaman ini"
- Pastikan user memiliki role yang sesuai
- Cek apakah route sudah ditambahkan dengan middleware yang benar

### Error: "Class not found"
- Jalankan `composer dump-autoload`
- Pastikan namespace controller sudah benar

### Dashboard tidak muncul setelah login
- Cek method `getDashboardRoute()` di model User
- Pastikan route dashboard sudah terdaftar

### Sidebar tidak muncul
- Pastikan menggunakan layout `layouts.sawit`
- Cek apakah file sidebar ada di `sawit/partials/sidebar.blade.php`

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi tim development.

---

**Dibuat:** 15 Mei 2026  
**Versi:** 1.0.0  
**Status:** ✅ Production Ready
