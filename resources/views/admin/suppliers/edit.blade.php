@extends('layouts.app')
@section('title', 'Ubah Supplier')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Ubah Supplier</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $supplier->name }}</p>
    </div>
    <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Kembali</a>
</div>

@include('partials.alert')

<form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 max-w-2xl">
    @csrf @method('PUT')
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama perusahaan / PT <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
    <button type="submit" class="mt-6 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:save-line"></iconify-icon> Perbarui Supplier
    </button>
</form>
@endsection
