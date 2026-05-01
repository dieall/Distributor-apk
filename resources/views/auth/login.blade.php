@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<section class="bg-white flex flex-wrap min-h-[100vh]">
    {{-- Gambar Kiri --}}
    <div class="lg:w-1/2 lg:block hidden bg-gradient-to-br from-primary-600 to-primary-800">
        <div class="flex items-center flex-col h-full justify-center p-12">
            <div class="text-center text-white mb-8">
                <h1 class="text-4xl font-bold mb-4">Distributor APK</h1>
                <p class="text-primary-100 text-lg">Sistem Manajemen Distribusi Terpadu</p>
            </div>
            <img src="{{ asset('assets/images/auth/auth-img.png') }}" alt="Auth Image" class="max-w-md w-full">
            <div class="mt-8 grid grid-cols-3 gap-4 text-center text-white">
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-2xl font-bold">5+</div>
                    <div class="text-sm text-primary-100">Role Pengguna</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-2xl font-bold">Real</div>
                    <div class="text-sm text-primary-100">Time Data</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-2xl font-bold">24/7</div>
                    <div class="text-sm text-primary-100">Support</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Login Kanan --}}
    <div class="lg:w-1/2 py-8 px-6 flex flex-col justify-center">
        <div class="lg:max-w-[464px] mx-auto w-full">
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center">
                        <iconify-icon icon="ri:truck-fill" class="text-white text-xl"></iconify-icon>
                    </div>
                    <span class="text-xl font-bold text-neutral-800">Distributor APK</span>
                </div>
                <h4 class="mb-2 text-2xl font-bold text-neutral-800">Selamat Datang! 👋</h4>
                <p class="text-secondary-light text-base">Masukkan email dan password untuk masuk ke akun Anda</p>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="bg-danger-50 border border-danger-200 text-danger-700 rounded-xl px-4 py-3 mb-6 flex items-center gap-3">
                    <iconify-icon icon="ri:error-warning-line" class="text-danger-500 text-xl flex-shrink-0"></iconify-icon>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-success-50 border border-success-200 text-success-700 rounded-xl px-4 py-3 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div class="icon-field mb-4 relative">
                    <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl text-secondary-light">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl w-full @error('email') border-danger-400 @enderror"
                        placeholder="Email"
                        required
                        autocomplete="email"
                    >
                </div>

                {{-- Password --}}
                <div class="relative mb-5">
                    <div class="icon-field">
                        <span class="absolute start-4 top-1/2 -translate-y-1/2 pointer-events-none flex text-xl text-secondary-light">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input
                            type="password"
                            name="password"
                            class="form-control h-[56px] ps-11 border-neutral-300 bg-neutral-50 rounded-xl w-full @error('password') border-danger-400 @enderror"
                            id="your-password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer absolute end-0 top-1/2 -translate-y-1/2 me-4 text-secondary-light" data-toggle="#your-password"></span>
                </div>

                {{-- Remember Me --}}
                <div class="flex justify-between gap-2 items-center">
                    <div class="flex items-center gap-2">
                        <input
                            class="form-check-input border border-neutral-300 rounded"
                            type="checkbox"
                            name="remember"
                            id="remember"
                        >
                        <label class="text-sm text-neutral-600" for="remember">Ingat saya</label>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="btn btn-primary justify-center text-sm btn-sm px-3 py-4 w-full rounded-xl mt-8 font-semibold"
                >
                    <iconify-icon icon="ri:login-box-line" class="me-2"></iconify-icon>
                    Masuk ke Sistem
                </button>
            </form>

            {{-- Role Info --}}
            <div class="mt-8 p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Akses Role Tersedia</p>
                <div class="grid grid-cols-5 gap-2">
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center mx-auto mb-1">
                            <iconify-icon icon="ri:shield-user-line" class="text-purple-600 text-sm"></iconify-icon>
                        </div>
                        <span class="text-xs text-neutral-500">Admin</span>
                    </div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mx-auto mb-1">
                            <iconify-icon icon="ri:store-2-line" class="text-blue-600 text-sm"></iconify-icon>
                        </div>
                        <span class="text-xs text-neutral-500">Gudang</span>
                    </div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mx-auto mb-1">
                            <iconify-icon icon="ri:bar-chart-line" class="text-green-600 text-sm"></iconify-icon>
                        </div>
                        <span class="text-xs text-neutral-500">Sales</span>
                    </div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center mx-auto mb-1">
                            <iconify-icon icon="ri:truck-line" class="text-orange-600 text-sm"></iconify-icon>
                        </div>
                        <span class="text-xs text-neutral-500">Supplier</span>
                    </div>
                    <div class="text-center">
                        <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center mx-auto mb-1">
                            <iconify-icon icon="ri:user-heart-line" class="text-pink-600 text-sm"></iconify-icon>
                        </div>
                        <span class="text-xs text-neutral-500">Pelanggan</span>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-neutral-400 mt-6">
                &copy; {{ date('Y') }} Distributor APK. Semua hak dilindungi.
            </p>
        </div>
    </div>
</section>
@endsection
