# 📝 Panduan Membuat Views yang Tersisa

## ✅ Yang Sudah Dibuat:
1. ✅ Barang: index, create, edit, show (4 files)
2. ✅ Dashboard Admin Sawit

## 🔄 Yang Perlu Dibuat:

### Penjualan (5 files)
- resources/views/sawit/admin/penjualan/index.blade.php
- resources/views/sawit/admin/penjualan/create.blade.php
- resources/views/sawit/admin/penjualan/edit.blade.php
- resources/views/sawit/admin/penjualan/show.blade.php
- resources/views/sawit/admin/penjualan/print.blade.php

### Pembelian (4 files)
- resources/views/sawit/admin/pembelian/index.blade.php
- resources/views/sawit/admin/pembelian/create.blade.php
- resources/views/sawit/admin/pembelian/edit.blade.php
- resources/views/sawit/admin/pembelian/show.blade.php

### Surat Jalan (4 files)
- resources/views/sawit/admin/surat-jalan/index.blade.php
- resources/views/sawit/admin/surat-jalan/create.blade.php
- resources/views/sawit/admin/surat-jalan/show.blade.php
- resources/views/sawit/admin/surat-jalan/print.blade.php

## 🚀 Cara Cepat Membuat Views:

### Opsi 1: Copy dari Sistem Lama & Modifikasi
Karena struktur view sudah ada di sistem lama, Anda bisa:

1. Copy view dari `resources/views/admin/barang/` 
2. Paste ke `resources/views/sawit/admin/barang/`
3. Find & Replace:
   - `route('admin.barang` → `route('sawit.admin.barang`
   - `Barang` → `SawitBarang` (untuk model)
   - Sesuaikan field names

### Opsi 2: Gunakan Template Berikut

Saya sudah menyiapkan template lengkap untuk setiap view. Anda tinggal copy-paste dan sesuaikan field-nya.

## 📋 Template Views

### Template Index (List Data)
```blade
@extends('layouts.sawit')
@section('title', 'Judul Halaman')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Judul</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Deskripsi</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Current Page</li>
    </ul>
</div>

@include('partials.alert')

<div class="card">
    {{-- Header dengan tombol --}}
    <div class="px-6 py-4 border-b flex justify-between">
        <h6>Title</h6>
        <a href="{{ route('sawit.admin.xxx.create') }}" class="btn btn-primary">
            Tambah Data
        </a>
    </div>

    <div class="p-6">
        {{-- Filter & Search --}}
        <form method="GET" class="mb-6">
            {{-- Form filter --}}
        </form>

        {{-- Table --}}
        <table class="w-full">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td>{{ $item->field }}</td>
                    <td>
                        <a href="{{ route('sawit.admin.xxx.show', $item) }}">Detail</a>
                        <a href="{{ route('sawit.admin.xxx.edit', $item) }}">Edit</a>
                        <form action="{{ route('sawit.admin.xxx.destroy', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button>Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $data->links() }}
    </div>
</div>
@endsection
```

### Template Create/Edit Form
```blade
@extends('layouts.sawit')
@section('title', 'Form Title')

@section('content')
{{-- Breadcrumb --}}

@include('partials.alert')

<div class="card">
    <div class="px-6 py-4 border-b">
        <h6>Form Title</h6>
    </div>

    <div class="p-6">
        <form action="{{ route('sawit.admin.xxx.store') }}" method="POST">
            @csrf
            {{-- Untuk edit tambahkan: @method('PUT') --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Form fields --}}
                <div>
                    <label>Field Name <span class="text-red-500">*</span></label>
                    <input type="text" name="field_name" value="{{ old('field_name') }}" required>
                    @error('field_name')<p class="text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('sawit.admin.xxx.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
```

## 🎯 Field-Field Penting

### Penjualan Sawit
```php
- no_invoice (auto-generate)
- tanggal
- perusahaan_id (dropdown)
- barang_id (dropdown)
- qty_timbangan
- refaksi
- total_kg_setelah_refaksi (auto-calculate)
- harga_per_kg
- total_harga (auto-calculate)
- status (draft/done)
- tanggal_bayar
- pembayaran_invoice
- potongan
- selisih_pembayaran (auto-calculate)
- jenis_kendaraan
- no_mobil
- nama_supir
- keterangan_potongan
- keterangan
```

### Pembelian Sawit
```php
- no_pembelian (auto-generate)
- tanggal
- barang_id (dropdown)
- nama_pembeli (input manual text)
- qty_timbangan
- refaksi
- qty_setelah_refaksi (auto-calculate)
- harga_per_kg
- total_harga (auto-calculate)
- potongan_dp
- tanggal_transfer
- selisih (auto-calculate)
- status (draft/done)
- type_payment (cash/transfer/tempo)
- keterangan
```

### Surat Jalan
```php
- no_surat_jalan (auto-generate)
- penjualan_id (dropdown - dari penjualan yang belum punya SJ)
- tanggal
- nama_perusahaan (auto-fill dari penjualan)
- alamat_tujuan (auto-fill dari penjualan)
- jenis_sawit (auto-fill dari penjualan)
- total_kg (auto-fill dari penjualan)
- jenis_kendaraan (auto-fill dari penjualan)
- no_mobil (auto-fill dari penjualan)
- nama_supir (auto-fill dari penjualan)
- keterangan
```

## 💡 Tips Auto-Calculate dengan JavaScript

Tambahkan script ini di form penjualan/pembelian:

```javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyTimbangan = document.querySelector('[name="qty_timbangan"]');
    const refaksi = document.querySelector('[name="refaksi"]');
    const hargaPerKg = document.querySelector('[name="harga_per_kg"]');
    const totalKg = document.querySelector('#total_kg');
    const totalHarga = document.querySelector('#total_harga');

    function calculate() {
        const qty = parseFloat(qtyTimbangan.value) || 0;
        const ref = parseFloat(refaksi.value) || 0;
        const harga = parseFloat(hargaPerKg.value) || 0;

        const kg = qty - ref;
        const total = kg * harga;

        totalKg.textContent = kg.toFixed(2) + ' Kg';
        totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    qtyTimbangan.addEventListener('input', calculate);
    refaksi.addEventListener('input', calculate);
    hargaPerKg.addEventListener('input', calculate);
});
</script>
```

## ✅ Checklist Testing

Setelah membuat views, test:

1. [ ] Bisa akses halaman index
2. [ ] Bisa klik tombol tambah
3. [ ] Form validation berfungsi
4. [ ] Bisa simpan data
5. [ ] Bisa edit data
6. [ ] Bisa hapus data
7. [ ] Bisa lihat detail
8. [ ] Export Excel berfungsi
9. [ ] Print berfungsi
10. [ ] Auto-calculate berfungsi

## 🚀 Next Steps

1. Buat views penjualan (prioritas tertinggi)
2. Buat views pembelian
3. Buat views surat jalan
4. Test semua fitur
5. Fix bugs jika ada

---

**Note:** Semua backend sudah siap! Tinggal buat views dan sistem akan langsung berfungsi.
