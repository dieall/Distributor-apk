@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Profil Saya</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Informasi pribadi akun Anda</p>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6 max-w-2xl">
    <div class="p-6">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6 flex flex-col sm:flex-row items-center gap-4">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-primary-500">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-primary-100 flex items-center justify-center">
                            <iconify-icon icon="ri:user-3-line" class="text-primary-600 text-4xl"></iconify-icon>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Foto Profil</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="block w-full text-sm text-neutral-500 dark:text-neutral-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary-50 file:text-primary-700
                        hover:file:bg-primary-100">
                    <p class="text-xs text-neutral-400 mt-1">Format JPG, PNG (Maks 2MB)</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Role</label>
                <input type="text" value="{{ $user->role_label }}" disabled
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-neutral-100 dark:bg-neutral-600 dark:text-neutral-300 cursor-not-allowed">
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
                    <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
