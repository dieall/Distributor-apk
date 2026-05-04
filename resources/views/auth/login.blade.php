@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<section class="bg-white dark:bg-dark-2 flex flex-wrap min-h-[100vh]">
    {{-- Kiri: ilustrasi (sama seperti Wowdash sign-in) --}}
    <div class="lg:w-1/2 lg:block hidden">
        <div class="flex items-center flex-col h-full min-h-[100vh] justify-center bg-neutral-50 dark:bg-neutral-900/40 px-8 border-e border-neutral-200 dark:border-neutral-700">
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="" class="max-w-lg w-full object-contain">
        </div>
    </div>

    {{-- Kanan: form login --}}
    <div class="w-full lg:w-1/2 py-10 px-6 sm:px-10 flex flex-col justify-center">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div class="mb-8">
                <a href="{{ route('login') }}" class="inline-block mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Distributor APK" class="max-h-10 w-auto dark:hidden">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="Distributor APK" class="max-h-10 w-auto hidden dark:block">
                </a>
                <h4 class="mb-2 text-neutral-800 dark:text-white font-semibold">Masuk ke akun Anda</h4>
                <p class="mb-0 text-secondary-light text-lg">Selamat datang kembali. Silakan masukkan email dan password.</p>
            </div>

            @if ($errors->any())
            <div class="bg-danger-50 border border-danger-200 text-danger-700 dark:bg-danger-500/10 dark:border-danger-500/30 dark:text-danger-300 rounded-xl px-4 py-3 mb-6 flex items-start gap-3">
                <iconify-icon icon="ri:error-warning-line" class="text-xl flex-shrink-0"></iconify-icon>
                <div class="text-sm">
                    @foreach ($errors->all() as $error)
                    <p class="mb-0 last:mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if (session('success'))
            <div class="bg-success-50 border border-success-200 text-success-700 dark:bg-success-500/10 dark:border-success-500/30 dark:text-success-300 rounded-xl px-4 py-3 mb-6 text-sm">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="icon-field mb-4 relative">
                    <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl text-secondary-light">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 dark:border-neutral-600 rounded-xl w-full @error('email') border-danger-400 @enderror"
                        placeholder="Email"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="relative mb-5">
                    <div class="icon-field">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl text-secondary-light">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 dark:bg-dark-2 dark:border-neutral-600 rounded-xl w-full @error('password') border-danger-400 @enderror"
                            id="your-password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                </div>

                <div class="mt-7">
                    <div class="flex justify-between gap-2 items-center flex-wrap">
                        <div class="flex items-center">
                            <input class="form-check-input border border-neutral-300 dark:border-neutral-600" type="checkbox" name="remember" id="remember" value="1">
                            <label class="ps-2 text-sm text-neutral-700 dark:text-neutral-300 mb-0 cursor-pointer" for="remember">Ingat saya</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8">
                    <iconify-icon icon="ri:login-box-line" class="me-2"></iconify-icon>
                    Masuk
                </button>
            </form>

            <div class="mt-8 p-4 rounded-xl bg-neutral-50 dark:bg-neutral-800/80 border border-neutral-200 dark:border-neutral-700">
                <p class="text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wide mb-2">Peran di sistem</p>
                <p class="text-sm text-secondary-light mb-0 leading-relaxed">
                    Admin, Gudang, Sales, Purchasing, dan Pelanggan menggunakan halaman login yang sama.
                    Data supplier hanya berupa nama PT di menu Data Supplier &mdash; <strong class="text-neutral-700 dark:text-neutral-200">tanpa akun login</strong>.
                </p>
            </div>

            <p class="text-center text-sm text-secondary-light mt-8 mb-0">
                &copy; {{ date('Y') }} Distributor APK
            </p>
        </div>
    </div>
</section>
@endsection
