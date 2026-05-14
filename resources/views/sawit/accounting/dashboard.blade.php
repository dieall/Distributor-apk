@extends('layouts.sawit')
@section('title', 'Dashboard Accounting Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Dashboard Accounting Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola keuangan dan pembayaran sawit</p>
    </div>
    <div class="flex items-center gap-2">
        <iconify-icon icon="ri:money-dollar-circle-line" class="text-success-600 text-2xl"></iconify-icon>
        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ now()->format('d F Y') }}</span>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:arrow-down-circle-line" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pemasukan</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['total_pemasukan'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-danger-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:arrow-up-circle-line" class="text-danger-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pengeluaran</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:line-chart-line" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Laba/Rugi</p>
                <h6 class="font-semibold mb-0 dark:text-white">Rp {{ number_format($stats['laba_rugi'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:time-line" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Pending Approval</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['pending_approval']) }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Welcome Card --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="p-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:money-dollar-circle-line" class="text-success-600 text-4xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <h5 class="font-semibold mb-1 dark:text-white">Accounting Sawit</h5>
                <p class="text-secondary-light text-sm mb-0">Kelola pembayaran petani, pengeluaran operasional, dan laporan keuangan sistem sawit.</p>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Aksi Cepat</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-success-500 hover:bg-success-50 dark:hover:bg-success-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:user-line" class="text-success-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Pembayaran Petani</p>
                    <p class="text-xs text-secondary-light mb-0">Proses pembayaran TBS</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-danger-500 hover:bg-danger-50 dark:hover:bg-danger-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-danger-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:wallet-3-line" class="text-danger-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Pengeluaran</p>
                    <p class="text-xs text-secondary-light mb-0">Catat pengeluaran</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Laporan Keuangan</p>
                    <p class="text-xs text-secondary-light mb-0">Lihat laporan</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
