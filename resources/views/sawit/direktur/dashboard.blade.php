@extends('layouts.sawit')
@section('title', 'Dashboard Direktur Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Dashboard Direktur Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Monitoring dan laporan sistem sawit</p>
    </div>
    <div class="flex items-center gap-2">
        <iconify-icon icon="ri:bar-chart-box-line" class="text-primary-600 text-2xl"></iconify-icon>
        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ now()->format('d F Y') }}</span>
    </div>
</div>

{{-- Alert Read-Only --}}
<div class="bg-info-50 dark:bg-info-900/20 border border-info-200 dark:border-info-800 rounded-lg p-4 mb-6">
    <div class="flex items-start gap-3">
        <iconify-icon icon="ri:information-line" class="text-info-600 text-xl flex-shrink-0 mt-0.5"></iconify-icon>
        <div>
            <p class="text-sm font-semibold text-info-900 dark:text-info-100 mb-1">Mode Read-Only</p>
            <p class="text-xs text-info-700 dark:text-info-300 mb-0">Anda memiliki akses untuk melihat semua data dan laporan, namun tidak dapat melakukan perubahan data.</p>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Transaksi</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_transaksi']) }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:money-dollar-circle-line" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pendapatan</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:shopping-cart-2-line" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pembelian</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['total_pembelian'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:line-chart-line" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Laba Bersih</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['laba_bersih'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Welcome Card --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:bar-chart-box-line" class="text-primary-600 text-4xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <h5 class="font-semibold mb-1 dark:text-white">Dashboard Direktur Sawit</h5>
                <p class="text-secondary-light text-sm mb-0">Pantau performa bisnis sawit melalui laporan produksi, penjualan, dan keuangan secara real-time.</p>
            </div>
        </div>
    </div>
</div>

{{-- Quick Access to Reports --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Akses Cepat Laporan</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-success-500 hover:bg-success-50 dark:hover:bg-success-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:plant-line" class="text-success-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Laporan Produksi</p>
                    <p class="text-xs text-secondary-light mb-0">TBS & CPO</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:line-chart-line" class="text-primary-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Laporan Penjualan</p>
                    <p class="text-xs text-secondary-light mb-0">Omzet & target</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-warning-500 hover:bg-warning-50 dark:hover:bg-warning-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:pie-chart-line" class="text-warning-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Laporan Keuangan</p>
                    <p class="text-xs text-secondary-light mb-0">Laba rugi & cashflow</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
