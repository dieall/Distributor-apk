@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white text-lg">Edit User</h6>
        <p class="text-secondary-light text-sm mb-0">Perbarui data akun: {{ $user->name }}</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium"><a href="{{ route('admin.users.index') }}" class="hover:text-primary-600">Manajemen User</a></li>
        <li>-</li>
        <li class="font-medium">Edit</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center gap-2">
        <iconify-icon icon="ri:edit-line" class="text-warning-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Edit: {{ $user->name }}</h6>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" autocomplete="off">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('name') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('email') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">Role / Hak Akses <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full px-4 py-2.5 text-sm border {{ $errors->has('role') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                        @foreach($roles as $r)
                            <option value="{{ $r }}" {{ old('role', $user->role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium dark:text-white mb-1">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2.5 text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                </div>

                {{-- Password (opsional) --}}
                <div class="p-4 bg-neutral-50 dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-600">
                    <p class="text-xs font-semibold text-secondary-light mb-3 uppercase tracking-wide">Ganti Password (opsional)</p>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium dark:text-white mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-2.5 text-sm border {{ $errors->has('password') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }} dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium dark:text-white mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                class="w-full px-4 py-2.5 text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300">
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-300"
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <label for="is_active" class="text-sm dark:text-white">Akun aktif</label>
                    @if($user->id === auth()->id())
                        <span class="text-xs text-secondary-light">(Tidak bisa menonaktifkan diri sendiri)</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-5 border-t border-neutral-100 dark:border-neutral-600">
                <button type="submit" class="btn btn-primary-600 px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
                    <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-6 py-2.5 rounded-lg text-sm font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
