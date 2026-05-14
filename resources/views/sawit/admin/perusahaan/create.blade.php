@extends('layouts.sawit')
@section('title', 'Tambah Perusahaan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div><h6 class="font-semibold mb-0 dark:text-white">Tambah Perusahaan</h6></div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.perusahaan.index') }}" class="hover:text-primary-600">Perusahaan</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Tambah</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Perusahaan</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('sawit.admin.perusahaan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('nama_perusahaan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Contact Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('alamat') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('keterangan') }}</textarea>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-neutral-300 text-success-600 focus:ring-success-500">
                        <span class="text-sm text-neutral-700 dark:text-neutral-200">Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('sawit.admin.perusahaan.index') }}" class="px-5 py-2.5 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:save-line"></iconify-icon> Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
