@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Tambah Pengeluaran</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Input biaya harian/operasional.</p>
    </div>
    <a href="{{ route('admin.pengeluaran.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Kembali</a>
</div>

@include('partials.alert')

<form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 max-w-3xl">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="kategori" value="{{ old('kategori', 'Operasional') }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
            <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: BBM pengiriman, parkir, makan driver" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nominal <span class="text-red-500">*</span></label>
            <input type="text" name="nominal" value="{{ old('nominal') }}" required class="input-ribuan w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white" placeholder="0">
            @error('nominal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Pengeluaran
    </button>
</form>
@endsection
