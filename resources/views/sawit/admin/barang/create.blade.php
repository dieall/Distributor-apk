@extends('layouts.sawit')
@section('title', 'Tambah Barang Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Tambah Barang Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Isi form berikut untuk menambah data barang sawit</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="text-base"></iconify-icon>Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.barang.index') }}" class="hover:text-primary-600">Data Barang</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Tambah</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
            <iconify-icon icon="ri:box-3-line" class="text-success-600 text-lg"></iconify-icon>
        </div>
        <div>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Barang Sawit</h6>
            <p class="text-secondary-light text-xs mb-0">Lengkapi semua field yang diperlukan</p>
        </div>
    </div>

    <div class="p-6">
        <form action="{{ route('sawit.admin.barang.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">ID Sawit <span class="text-red-500">*</span></label>
                    <input type="text" name="id_sawit" value="{{ old('id_sawit', $autoIdSawit) }}" placeholder="Contoh: SWT-0001" maxlength="50" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 {{ $errors->has('id_sawit') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    @error('id_sawit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-neutral-400 text-xs mt-1">ID unik untuk barang sawit</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Sawit <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_sawit" value="{{ old('nama_sawit') }}" placeholder="Contoh: TBS (Tandan Buah Segar)" required
                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 {{ $errors->has('nama_sawit') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    @error('nama_sawit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan <span class="text-neutral-400 font-normal">(opsional)</span></label>
                    <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan tentang barang sawit..."
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('keterangan') }}</textarea>
                    @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-success-600 focus:ring-success-500">
                        <span class="text-sm text-neutral-700 dark:text-neutral-200">Aktif</span>
                    </label>
                    <p class="text-neutral-400 text-xs mt-1">Centang jika barang ini aktif dan bisa digunakan dalam transaksi</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('sawit.admin.barang.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-600 transition flex items-center gap-2">
                    <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
                    <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
