@extends('layouts.app')
@section('title', 'Dashboard Supplier')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h6 class="font-semibold mb-0 dark:text-white">Dashboard Supplier</h6>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('supplier.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium">Supplier</li>
    </ul>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Order Masuk</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-warning-100 text-warning-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:truck-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Purchase order aktif</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Terkirim</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-success-100 text-success-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:checkbox-circle-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Pengiriman selesai</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Produk</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-primary-100 text-primary-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:box-3-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Total produk ditawarkan</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Pending</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-warning-100 text-warning-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:time-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Menunggu konfirmasi</p>
        </div>
    </div>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg mb-6">
    <div class="card-body p-6">
        <div class="flex items-center gap-4">
            <div class="w-[64px] h-[64px] bg-warning-100 rounded-full flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:truck-line" class="text-warning-600 text-3xl"></iconify-icon>
            </div>
            <div>
                <h5 class="font-semibold mb-1 dark:text-white">Selamat Datang, {{ auth()->user()->name }}!</h5>
                <p class="text-secondary-light mb-0 text-sm">
                    Anda masuk sebagai <strong class="text-warning-600">Supplier</strong>.
                    Kelola katalog produk dan proses order pembelian dari distributor.
                </p>
            </div>
        </div>
    </div>
</div>

<h6 class="font-semibold mb-4 dark:text-white">Akses Cepat</h6>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-warning-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:file-list-line" class="text-warning-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Lihat Order</p>
                <span class="text-secondary-light text-xs">Purchase order</span>
            </div>
        </div>
    </a>
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:box-3-line" class="text-blue-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Katalog Produk</p>
                <span class="text-secondary-light text-xs">Kelola produk</span>
            </div>
        </div>
    </a>
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-success-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:file-text-line" class="text-success-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Invoice</p>
                <span class="text-secondary-light text-xs">Kelola invoice</span>
            </div>
        </div>
    </a>
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:history-line" class="text-purple-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Riwayat Kirim</p>
                <span class="text-secondary-light text-xs">Histori pengiriman</span>
            </div>
        </div>
    </a>
</div>
@endsection
