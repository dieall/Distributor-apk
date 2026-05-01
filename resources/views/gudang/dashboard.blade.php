@extends('layouts.app')
@section('title', 'Dashboard Gudang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h6 class="font-semibold mb-0 dark:text-white">Dashboard Gudang</h6>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('gudang.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium">Gudang</li>
    </ul>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Total Produk</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-blue-100 text-blue-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:box-3-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Jenis produk tersedia</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Penerimaan</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-success-100 text-success-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:inbox-archive-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Barang masuk bulan ini</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Pengeluaran</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-warning-100 text-warning-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:send-plane-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Barang keluar bulan ini</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Stok Menipis</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-danger-100 text-danger-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:alert-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Produk perlu restock</p>
        </div>
    </div>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg mb-6">
    <div class="card-body p-6">
        <div class="flex items-center gap-4">
            <div class="w-[64px] h-[64px] bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:store-2-line" class="text-blue-600 text-3xl"></iconify-icon>
            </div>
            <div>
                <h5 class="font-semibold mb-1 dark:text-white">Selamat Datang, {{ auth()->user()->name }}!</h5>
                <p class="text-secondary-light mb-0 text-sm">
                    Anda masuk sebagai <strong class="text-blue-600">Petugas Gudang</strong>.
                    Kelola stok dan pergerakan barang gudang dengan efisien.
                </p>
            </div>
        </div>
    </div>
</div>

<h6 class="font-semibold mb-4 dark:text-white">Akses Cepat</h6>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
    <a href="{{ route('gudang.stok.index') }}" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:store-2-line" class="text-blue-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Cek Stok Barang</p>
                <span class="text-secondary-light text-xs">Lihat inventori</span>
            </div>
        </div>
    </a>
    <a href="{{ route('gudang.penerimaan.index') }}" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-success-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:inbox-archive-line" class="text-success-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Terima Barang</p>
                <span class="text-secondary-light text-xs">Input penerimaan</span>
            </div>
        </div>
    </a>
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-warning-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:send-plane-line" class="text-warning-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Kirim Barang</p>
                <span class="text-secondary-light text-xs">Proses pengiriman</span>
            </div>
        </div>
    </a>
    <a href="#" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg p-5 text-decoration-none hover:border-primary-300 transition-all duration-200 block">
        <div class="flex items-center gap-3">
            <div class="w-[40px] h-[40px] bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:file-list-3-line" class="text-purple-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="font-medium text-neutral-800 dark:text-white mb-0 text-sm">Laporan Stok</p>
                <span class="text-secondary-light text-xs">Cetak laporan</span>
            </div>
        </div>
    </a>
</div>
@endsection
