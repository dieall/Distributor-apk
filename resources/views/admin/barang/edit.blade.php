@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Edit Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $barang->kode }} — {{ $barang->nama }}</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="text-base"></iconify-icon>Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('admin.barang.index') }}" class="hover:text-primary-600">Data Barang</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Edit</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-warning-100 flex items-center justify-center">
                <iconify-icon icon="ri:edit-box-line" class="text-warning-600 text-lg"></iconify-icon>
            </div>
            <div>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Edit Informasi Barang</h6>
                <p class="text-secondary-light text-xs mb-0">Perbarui data barang sesuai kebutuhan</p>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full bg-primary-100 text-primary-600 text-xs font-semibold">{{ $barang->kode }}</span>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.barang.update', $barang) }}" method="POST">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode" value="{{ old('kode', $barang->kode) }}" maxlength="20" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('kode') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    @error('kode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('nama') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
                        @foreach(['Sayuran','Buah-buahan','Daging & Ikan','Bumbu & Rempah','Beras & Serealia','Minyak & Lemak','Susu & Telur','Minuman','Umum'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Satuan <span class="text-red-500">*</span></label>
                    <select name="satuan" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
                        @foreach(['kg','gram','liter','ml','pcs','lusin','karton','dus','ikat','buah','bungkus','botol','kaleng'] as $sat)
                        <option value="{{ $sat }}" {{ old('satuan', $barang->satuan) == $sat ? 'selected' : '' }}>{{ $sat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Harga Jual <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-neutral-500 font-medium">Rp</span>
                        <input type="text" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" required
                            class="input-ribuan w-full pl-12 pr-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Stok Minimum <span class="text-red-500">*</span></label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Deskripsi <span class="text-neutral-400 font-normal">(opsional)</span></label>
                    <textarea name="deskripsi" rows="3" placeholder="Keterangan tambahan..."
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-2">Status Barang</label>
                    <label class="flex items-center gap-3 cursor-pointer w-fit">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $barang->is_active) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-neutral-200 rounded-full peer peer-checked:bg-primary-600 transition-all"></div>
                            <div class="absolute top-[2px] left-[2px] w-5 h-5 bg-white rounded-full shadow-sm transition-all peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-200">Barang Aktif</span>
                        <span class="text-xs text-secondary-light">(non-aktif tidak muncul di form order)</span>
                    </label>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('admin.barang.show', $barang) }}"
                    class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
                    <iconify-icon icon="ri:eye-line"></iconify-icon> Lihat Detail
                </a>
                <a href="{{ route('admin.barang.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
                    <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
                    <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
