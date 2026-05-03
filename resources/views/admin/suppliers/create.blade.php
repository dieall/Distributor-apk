@extends('layouts.app')
@section('title', 'Tambah Supplier')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Tambah Supplier</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Data seperti PT Supplier Jaya diinput oleh administrator.</p>
    </div>
    <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Kembali</a>
</div>

@include('partials.alert')

<form action="{{ route('admin.suppliers.store') }}" method="POST" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 max-w-2xl">
    @csrf
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama perusahaan / PT <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT Supplier Jaya"
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Email login <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off"
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Ulangi password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Opsional"
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active" class="text-sm text-neutral-700 dark:text-neutral-200">Akun aktif (dapat login)</label>
        </div>
    </div>
    <button type="submit" class="mt-6 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Supplier
    </button>
</form>
@endsection
