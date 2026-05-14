@extends('layouts.app')
@section('title', 'Pengaturan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Pengaturan</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Ubah kata sandi Anda</p>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6 max-w-2xl">
    <div class="p-6">
        <form action="{{ route('profile.update-password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('current_password')
                    <p class="text-danger-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                @error('password')
                    <p class="text-danger-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
                    <iconify-icon icon="ri:lock-password-line"></iconify-icon> Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
