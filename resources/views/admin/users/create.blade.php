@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white text-lg">Tambah User Baru</h6>
        <p class="text-secondary-light text-sm mb-0">Buat akun login baru beserta hak akses role</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium"><a href="{{ route('admin.users.index') }}" class="hover:text-primary-600">Manajemen User</a></li>
        <li>-</li>
        <li class="font-medium">Tambah</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center gap-2">
        <iconify-icon icon="ri:user-add-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Data Akun User</h6>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" autocomplete="off">
            @csrf

            <div class="grid grid-cols-1 gap-5">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('name') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('email') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Role / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full px-4 py-2.5 text-sm border {{ $errors->has('role') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-secondary-light mt-1">Admin & Direktur: akses penuh; Gudang/Sales/Purchasing: akses terbatas</p>
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-2.5 text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('password') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="w-full px-4 py-2.5 text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                </div>

                {{-- Status Aktif --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-300">
                    <label for="is_active" class="text-sm dark:text-white">Aktifkan akun ini</label>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-5 border-t border-neutral-100 dark:border-neutral-600">
                <button type="submit" class="btn btn-primary-600 px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
                    <iconify-icon icon="ri:save-line"></iconify-icon> Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-6 py-2.5 rounded-lg text-sm font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
